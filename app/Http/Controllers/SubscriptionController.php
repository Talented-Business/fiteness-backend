<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Exports\SubscriptionsExport;
use App\PaymentSubscription;
use App\PayPalPlan;
use App\Subscription;
use App\SubscriptionPlan;
use App\PaymentPlan;
use App\NmiClient;
use App\Transaction;
use App\PaymentTocken;
use Fahim\PaypalIPN\PaypalIPNListener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Cart;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $paymentSubscription = new PaymentSubscription;
        $paymentSubscription->assign($request);
        $now = $paymentSubscription->updateSubscription();
        $cart = Cart::whereCustomerId($user->customer->id)->first();
        if($cart)$cart->delete();
        return response()->json(['status' => 'ok', 'now' => $now]);
    }
    public function show($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('subscriptions')){
            $subscription = Subscription::find($id);
            $subscription->customer;
            $subscription['serviceName'] = $subscription->plan->service->title;
            $subscription['startDate'] = date('d/m/y',strtotime($subscription->start_date));
            if($subscription->end_date)$subscription['endDate'] = date('d/m/y',strtotime($subscription->end_date));
            if($subscription->cancelled_date)$subscription['cancelledDate'] = date('d/m/y',strtotime($subscription->cancelled_date));
            if($subscription->end_date && $subscription->status == 'Active'){
                $subscription->status = 'LEAVING';
            }
            return response()->json($subscription);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function index(Request $request)
    {
        $user = $request->user('api');
        if($user->can('subscriptions')){
            $subscription = new Subscription;
            $subscription->assignSearch($request);
            return response()->json($subscription->search());
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function free(Request $request)
    {
        $user = $request->user('api');
        if ($user->customer) {
            if ($user->customer->hasSubscription()) {
                return response()->json(['status' => 'failed', 'errors' => 'already taken workout subscription']);
            }
        } else {
            return response()->json(['status' => 'failed', 'errors' => 'no customer']);
        }
        $subscription = new Subscription;
        $plan = SubscriptionPlan::where('service_id', 1)->where('type', 'Free')->first();
        $subscription->plan_id = $plan->id;
        $subscription->customer_id = $user->customer->id;
        $subscription->start_date = date('Y-m-d');
        $subscription->end_date = date('Y-m-d', strtotime(" +" . $plan->free_duration . " days"));
        $subscription->frequency = "Once";
        $subscription->save();
        //Mail::to($user->email)->send(new VerifyMail($user));
        return response()->json(['status' => 'ok', 'subscription' => $subscription]);
    }
    public function export(Request $request)
    {
        $user = $request->user('api');
        if($user->can('subscriptions')){
            $subscription = new Subscription;
            $subscription->assignSearch($request);
            $subscriptions = $subscription->searchAll();
            $itemsArray = [];
            $itemsArray[] = ['ID', 'Subscription Type', 'Customer', 'Subscription Date', 'Total', 'Status'];
            $total = 0;
            foreach ($subscriptions as $subscription) {
                $itemsArray[] = [$subscription->id,
                    $subscription->content,
                    $subscription->customer->first_name . ' ' . $subscription->customer->last_name,
                    $subscription->created_at,
                    $subscription->total,
                    $subscription->status,
                ];
            }
            $export = new SubscriptionsExport([
                $itemsArray,
            ]);
            return Excel::download($export, 'subscriptions.xlsx');
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function checkout()
    {
        return response()->json(['mode' => env('PAYMENT_TEST_MODE')]);
    }
    public function renewal($id,Request $request){
        $user = $request->user('api');
        $frequency = $request->input('frequency');
        switch($frequency){
            case 'monthly':
                $frequency = 1;
            break;
            case 'quarterly':
                $frequency = 3;
            break;
            case 'semiannual':
                $frequency = 6;
            break;
            case 'yearly':
                $frequency = 12;
            break;
        }
        $subscription = Subscription::find($id);
        if($subscription->customer_id == $user->customer->id){
            $paymentPlan = PaymentPlan::createOrUpdate($subscription->plan, $subscription->customer_id, $subscription->coupon, $frequency,'nmi');
            $paymentSubscription = new PaymentSubscription;
            if($subscription->end_date!=null){
                $subscription->end_date=null;
                $subscription->save();
            }
            list($now,$paymentSubscription) = $paymentSubscription->createFromPlan($subscription,$paymentPlan);
            return response()->json(['status' => 'success', 'now' => $now]);
        }else{
            return response()->json(['status' => 'failed', 'errors'=>'customer is wrong'],422);
        }
    }
    public function nmi(Request $request){
        //make plan
        $plan = SubscriptionPlan::where('service_id', '=', $request->input('service_id'))->where('type', '=', 'Paid')->first();
        $user = $request->user('api');
        if($request->exists('coupon')){
            $coupon = Coupon::find($request->input('coupon'));
        }else{
            $coupon = null;
        }
        $frequency = $request->input('frequency');
        //$planSlug = PaymentPlan::createSlug($plan, $user->customer->id, $coupon, $frequency,'nmi');
        //create or update plan 
        $paymentPlan = PaymentPlan::createOrUpdate($plan, $user->customer->id, $coupon, $frequency,'nmi');
        //find subscription, servicePlanId, customerId,
        $subscription = Subscription::findOrCreate($user->customer->id,$plan, $coupon,$frequency,'nmi');
        //create paymentSubscription and 
        $paymentSubscription = new PaymentSubscription;
        $testing = false;
        if($testing){
            $paymentSubscription = PaymentSubscription::find(6);
            $now = true;
        }else {
            list($now,$paymentSubscription) = $paymentSubscription->createFromPlan($subscription,$paymentPlan,true);
        }
        //create transaction(order);
        if( $now === true ){
            if($testing){
                $transaction = Transaction::find(9);
            }else{
                $transaction = Transaction::generate($paymentSubscription,$plan,$subscription);
            }
            //processing with nmi;
            $nmiClient = new NmiClient;
            $response = $nmiClient->processSubscription($transaction,$request);
            //return response;
            if($response && $response['result'] == 'success' ){
                $now = $paymentSubscription->updateSubscription($transaction);
                $paymentSubscription->firstSendMail($transaction);
            }else{
                return response()->json(['status' => 'failed', 'now' => $now,'errors'=>$response['error_message']],422);
            }
        }else{
            $nmiClient = new NmiClient;
            $response = $nmiClient->addCustomerVault($user->customer, $request);
            if(isset($response['result']) && $response['result'] == 'success' ){
                
            }else{
                return response()->json(['status' => 'failed', 'now' => $now,'errors'=>$response['error_message']],422);
            }
        }
        Cart::whereCustomerId($user->customer->id)->delete();
        //print_r($planSlug);
        //get price, frequency from coupon_id,
        return response()->json(['status' => 'ok', 'now' => $now]);
    }
    public function findPaypalPlan(Request $request)
    {
        $plan = SubscriptionPlan::where('service_id', '=', 1)->where('type', '=', 'Paid')->first();
        if ($request->exists('coupon_id')) {
            $couponId = $request->input('coupon_id');
            $coupon = Coupon::find($couponId);
        } else {
            $coupon = null;
        }
        $frequency = $request->input('frequency');
        $user = $request->user('api');
        $customer = $user->customer;
        $paypalPlanId = PayPalPlan::createOrUpdate($plan, $customer, $coupon, $frequency);
        $subscription = Subscription::whereCustomerId($customer->id)->where(function ($query) use ($plan) {
            $query->wherePlanId($plan->id);
        })->first();
        if ($subscription && $subscription->status == 'Active') {
            $startTime = Subscription::convertIsoDateToString(strtotime($subscription->nextPaymentTime()));
            $paypalInfo = ['paypalPlanId' => $paypalPlanId, 'startTime' => $startTime];
        }else{
            $paypalInfo = ['paypalPlanId' => $paypalPlanId];
        }
        if ($paypalPlanId) {
            return response()->json($paypalInfo);
        } else {
            return response()->json(['error' => 'error'], 422);
        }
    }
    public function cancel(Request $request)
    {
        $serviceId = $request->input('serviceId');
        $enableEnd = $request->input('enableEnd');
        $reason = $request->input('reason');
        $credit = $request->input('credit');
        $user = $request->user('api');
        $customer = $user->customer;
        $subscription = Subscription::whereCustomerId($customer->id)->where(function ($query) use ($serviceId) {
            $query->whereHas('plan', function ($q) use ($serviceId) {
                $q->whereServiceId($serviceId);
            });
        })->first();
        if ($subscription) {
            $success = $subscription->cancel($enableEnd, $reason,$credit);
        }

        if ($subscription && $success === true) {
            return response()->json(['success' => 'success']);
        }

        return response()->json(['error' => 'error'], 422);
    }
    public function disable($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('subscriptions')){
            $subscription = Subscription::find($id);
            if ($subscription) {
                $subscription->status = 'Cancelled';
                $subscription->cancelled_date = date('Y-m-d H:i:s');
                $subscription->cancelled_reason = 'Cancelled by admin';
                $subscription->cancelled_now = 'yes';
                $subscription->save();
                return response()->json(['success' => 'success']);
            }
            return response()->json(['error' => 'error'], 422);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function restore($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('subscriptions')){
            $subscription = Subscription::find($id);
            if ($subscription) {
                $subscription->status = 'Active';
                $subscription->cancelled_date = null;
                $subscription->cancelled_reason = null;
                $subscription->cancelled_now = null;
                $subscription->save();
                return response()->json(['success' => 'success']);
            }
            return response()->json(['error' => 'error'], 422);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function paypalIpn(Request $request)
    {
        $ipn = new PaypalIPNListener();
        $ipn->use_sandbox = env('PAYMENT_TEST_MODE');

        $verified = $ipn->processIpn();

        $report = $ipn->getTextReport();

        Log::channel('paypalPayments')->info("-----new payment-----");

        Log::channel('paypalPayments')->info($report);

        if ($verified) {
            //transaction insert, subscription paypal cancel, suspend by user or merchant
            if (isset($_POST['txn_type']) && isset($_POST['recurring_payment_id'])) {
                $paymentSubscription = PaymentSubscription::whereSubscriptionId($_POST['recurring_payment_id'])->first();
                if ($paymentSubscription) {
                    switch ($_POST['txn_type']) {
                        case 'recurring_payment_profile_created':
                            break;
                        case 'recurring_payment':
                            $paymentSubscription->transaction = 'Changed';
                            $paymentSubscription->save();
                            //$paymentSubscription->renewalSendMail($_POST['amount']);
                            break;
                        case 'recurring_payment_failed':
                            break;
                        case 'recurring_payment_profile_cancel':
                            $paymentSubscription->cancel();
                            break;
                        case 'recurring_payment_suspended':
                            $paymentSubscription->suspend();
                            break;
                        case 'recurring_payment_expired':
                            break;
                    }
                    Log::channel('paypalPayments')->info("payment verified and inserted and updated to payment_subscriptions table");
                }
            } else if (isset($_POST['address_status']) && $_POST['address_status'] == 'confirmed') {
                Log::channel('paypalPayments')->info(" other ipn if address_status");
            } else {
                Log::channel('paypalPayments')->info(" other ipn ");
            }
        } else {
            Log::channel('paypalPayments')->info("Some thing went wrong in the payment !");
        }
    }
}
