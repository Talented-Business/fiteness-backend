<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Mail\FirstPaymentNotification;
use App\Mail\RenewalPaymentNotification;
use App\Mail\VerifyMail;
use App\Jobs\SendEmail;
use App\Jobs\NotifySubscriber;
use App\Mail\MailQueue;
use App\Mail\NotifyNewCustomer;
use Mail;

class PaymentSubscription extends Model
{
    protected $table = 'payment_subscriptions';
    protected $fillable = ['subscription_id', 'plan_id', 'start_time'];
    private $item;
    private $pageSize;
    private $pageNumber;
    private static $searchableColumns = ['search'];
    private $paymentplan;
    public static function validateRules()
    {
        return array(
            'subscription_id' => 'required',
            'plan_id' => 'required',
            'start_time' => 'required',
        );
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public function assign($request)
    {
        $data = $request->input('data');
        $orderId = $data['orderID'];
        $user = $request->user('api');
        $customerId = $user->customer->id;
        $subscriptionId = $data['subscriptionID'];
        $paymentProvider = $request->input('paymentProvider');
        switch ($paymentProvider) {
            case 'nmi':
                break;
            case 'paypal':
                //$this->findPayPalOrder($orderId);
                $this->findPayPalSubscription($subscriptionId, $data, $customerId);
                break;
        }
    }
    private function findPayPalOrder($id)
    {
        $client = new \GuzzleHttp\Client();
        list($baseUrl, $clientId, $clientSecret) = PayPalClient::findKeys();
        try {
            $res = $client->get($baseUrl . '/v2/checkout/orders/' . $id, [
                'auth' => [$clientId, $clientSecret, 'basic'],
                'headers' => [
                    'Accept-Language' => 'en_US',
                    'Accept' => 'application/json',
                ],
            ]);
            $data = json_decode($res->getBody(), true);
            print_r($data);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            echo \GuzzleHttp\Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo \GuzzleHttp\Psr7\str($e->getResponse());
            }
            die;
        }
    }
    public function createWithBank($subscription, $paymentPlan){
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentPlan->analyzeSlug();
        $this->provider = $provider;
        $this->plan_id = $paymentPlan->plan_id;
        $this->subscription_id = $paymentPlan->plan_id . '-' . time();
        $this->customer_id = $customerId;
        $startTime = $subscription->getExpirationTime();
        if ($startTime && strtotime($startTime) > time()) {
            $this->start_time = Subscription::convertIsoDateToString(strtotime($startTime));
            $this->start_date = $startTime;
        } else {
            $this->start_time = Subscription::convertIsoDateToString(time());
            $this->start_date = date('Y-m-d H:i:s', time());
            $now = true;
        }
        $this->status = 'Active';
        $this->save();
    }
    public function createFromPlan($subscription, $paymentPlan)//nmi and paypal not bank
    {
        $now = false;
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentPlan->analyzeSlug();
        $paymentSubscription = self::whereCustomerId($customerId)->whereStatus('Approved')->first();
        if($paymentSubscription){
            $paymentSubscription->plan_id = $paymentPlan->plan_id;
            $paymentSubscription->subscription_id = $paymentPlan->plan_id . '-' . time();
            $startTime = $subscription->nextPaymentTime();
            if ($startTime && strtotime($startTime) > time()) {
                $paymentSubscription->start_time = Subscription::convertIsoDateToString(strtotime($startTime));
                $paymentSubscription->start_date = $startTime;
            } else {
                $paymentSubscription->start_time = Subscription::convertIsoDateToString(time());
                $paymentSubscription->start_date = date('Y-m-d H:i:s', time());
                $now = true;
            }
            $paymentSubscription->save();
            return [$now,$paymentSubscription];
        }else{
            $this->provider = $provider;
            $this->plan_id = $paymentPlan->plan_id;
            $this->subscription_id = $paymentPlan->plan_id . '-' . time();
            $this->customer_id = $customerId;
            $startTime = $subscription->nextPaymentTime();
            if ($startTime && strtotime($startTime) > time()) {
                $this->start_time = Subscription::convertIsoDateToString(strtotime($startTime));
                $this->start_date = $startTime;
            } else {
                $this->start_time = Subscription::convertIsoDateToString(time());
                $this->start_date = date('Y-m-d H:i:s', time());
                $now = true;
            }
            $this->status = 'Approved';
            $this->save();
        }
        return [$now,$this];
    }
    private function findPayPalSubscription($id, $data, $customerId)
    {
        $item = self::whereProvider('paypal')->whereSubscriptionId($id)->first();
        if ($item === null) {
            $client = new \GuzzleHttp\Client();
            list($baseUrl, $clientId, $clientSecret) = PayPalClient::findKeys();
            try {
                $res = $client->get($baseUrl . '/v1/billing/subscriptions/' . $id, [
                    'auth' => [$clientId, $clientSecret, 'basic'],
                    'headers' => [
                        'Accept-Language' => 'en_US',
                        'Accept' => 'application/json',
                    ],
                ]);
                $data = json_decode($res->getBody(), true);
                $this->provider = 'paypal';
                $this->plan_id = $data['plan_id'];
                $this->subscription_id = $id;
                $this->customer_id = $customerId;
                $this->start_time = $data['start_time'];
                $this->start_date = date('Y-m-d H:i:s', strtotime($data['start_time']));
                $this->status = ucfirst(strtolower($data['status']));
                $this->save();
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                echo \GuzzleHttp\Psr7\str($e->getRequest());
                if ($e->hasResponse()) {
                    echo \GuzzleHttp\Psr7\str($e->getResponse());
                }
                die;
            }
        } else {
            $this->provider = $item->provider;
            $this->plan_id = $item->plan_id;
            $this->subscription_id = $item->subscription_id;
            $this->start_time = $item->start_time;
            $this->start_date = $item->start_date;
            $this->status = $item->status;
            $this->customer_id = $item->customer_id;
            $this->id = $item->id;
        }
    }
    public function getCycles()//On Paypal
    {
        switch($this->provider){
            case 'nmi':
                $items = Transaction::wherePaymentSubscriptionId($this->subscription_id)->whereStatus('Completed')->get();
                $cyles = count($items);
            break;
            case 'paypal':
                $client = new \GuzzleHttp\Client();
                list($baseUrl, $clientId, $clientSecret) = PayPalClient::findKeys();
                $cyles = 0;
                try {
                    $res = $client->get($baseUrl . '/v1/billing/subscriptions/' . $this->subscription_id, [
                        'auth' => [$clientId, $clientSecret, 'basic'],
                        'headers' => [
                            'Accept-Language' => 'en_US',
                            'Accept' => 'application/json',
                        ],
                    ]);
                    $data = json_decode($res->getBody(), true);
                    foreach ($data['billing_info']['cycle_executions'] as $record) {
                        $cyles += $record['cycles_completed'];
                    }
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    /*echo \GuzzleHttp\Psr7\str($e->getRequest());
                    if ($e->hasResponse()) {
                        echo \GuzzleHttp\Psr7\str($e->getResponse());
                    }*/
                    $items = Transaction::wherePaymentSubscriptionId($this->subscription_id)->whereStatus('Completed')->get();
                    $cyles = count($items);
                }
            break;
        }
        return $cyles;
    }
    public function firstProcessingWithCustomerVault($subscription){
        $paymentProviders = ['nmi'];
        if (in_array($this->provider, $paymentProviders)) {
            //last paymentSubscription;
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $this->analyzeSlug();
            if (in_array($provider, $paymentProviders) && ($this->status == 'Active' || $this->status == 'Approved')) {
                if ($provider == 'nmi') {
                    if(in_array($customerId, Subscription::TRACK_CUSTOMER_IDS))Log::channel('nmiTrack')->info("---- firstProcessingWithCustomerVault----");
                    $nextPaymentTime = date('Y-m-d H:i:s',strtotime($subscription->start_date) + $subscription->plan->free_duration*3600*24);
                    if($nextPaymentTime && date('Y-m-d H:i:s')>$nextPaymentTime ){
                        $result = $this->firstPayNmi($subscription);
                        if(isset($result['transaction'])){
                            $subscription->plan_id = $planId;
                            $subscription->meta = $slug;
                            $subscription->transaction_id = $result['transaction']->id;
			                if($subscription->end_date)$subscription->end_date = null;
                            $subscription->save();
                        }else{
                            if(in_array($customerId, Subscription::TRACK_CUSTOMER_IDS))Log::channel('nmiTrack')->info("---- end_date ----");
                            $subscription->end_date = date('Y-m-d H:i:s');
                            $subscription->save();
                        }
                        print_r('Nmi first request');
                    }else{
                        print_r('None Nmi first request');
                    }
                    print_r("\n");
                } else {

                }
            }
        }
    }
    private function firstPayNmi(){
        $transaction = Transaction::renewalGenerate($this);
        if ($transaction->total > 0 ) {
            try {

                $nmiClient = new NmiClient;
                $response = $nmiClient->scheduledSubscriptionPayment($transaction);
                if($this->status == 'Approved'){
                    $this->status = 'Active';
                    $this->transaction = 'Done';
                    $this->save();
                }
                $this->sendFirstMail($transaction,false);
                    // Return thank you page redirect
                return array(
                    'result' => 'success',
                    'transaction'=>$transaction
                );

            } catch (\Exception $e) {
                //wc_add_notice( sprintf( __( 'Gateway Error: %s', 'wc-nmi' ), $e->getMessage() ), 'error' );
                Log::channel('nmiPayments')->error(sprintf('Gateway Error: %s', $e->getMessage()));
                return [
                    'result' => 'failed',
                    'error_message' => $e->getMessage(),
                ];
            }
        }else{
            if($transaction->coupon_id!=null){
                $coupon = Coupon::find($transaction->coupon_id);
                if(($coupon->discount == 100 && $coupon->form == '%' || $transaction->total == 0) && $coupon->status == 'Active' && $coupon->renewal == 1){
                    $transaction->status = 'Completed';
                    $transaction->save();
                }
            }
        }
        return array(
            'result' => 'success',
            'transaction'=>$transaction
        );
    }
    public function recurringProcessingWithCustomerVault($subscription)
    {
        $paymentProviders = ['nmi'];
        if (in_array($this->provider, $paymentProviders)) {
            //last paymentSubscription;
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $this->analyzeSlug();
            if (in_array($provider, $paymentProviders) && ($this->status == 'Active' || $this->status == 'Approved')) {
                if(in_array($customerId, Subscription::TRACK_CUSTOMER_IDS))Log::channel('nmiTrack')->info("---- Payment subscription active or approved----");
                if ($provider == 'nmi') {
                    $nextPaymentTime = $this->getEndDate($subscription->transaction);
                    if($subscription->transaction && $subscription->transaction->status == 'Pending') $nextPaymentTime = date($subscription->transaction->created_at);
                    if($nextPaymentTime == null && $subscription->plan_id == 2)$nextPaymentTime = date('Y-m-d H:i:s', time()-10);
                    if(in_array($customerId, Subscription::TRACK_CUSTOMER_IDS))Log::channel('nmiTrack')->info("---- Nmi start----");
                    if($nextPaymentTime && date('Y-m-d H:i:s')>$nextPaymentTime){
                        $this->recurringNmi();
                        if(in_array($customerId, Subscription::TRACK_CUSTOMER_IDS))Log::channel('nmiTrack')->info("---- Nmi reqiest----");
                    }else{
                        if(in_array($customerId, Subscription::TRACK_CUSTOMER_IDS))Log::channel('nmiTrack')->info("---- Nmi non request----");
                    }
                    if(in_array($customerId, Subscription::TRACK_CUSTOMER_IDS))Log::channel('nmiTrack')->info("---- Nmi end----");
                } else {
                    if(in_array($customerId, Subscription::TRACK_CUSTOMER_IDS))Log::channel('nmiTrack')->info("---- Non Nmi----");
                }
            }
        }
        //next pay with customer vault
    }
    private function recurringNmi(){
        $transaction = Transaction::renewalGenerate($this);
        if ($transaction->total > 0 ) {
            try {

                $nmiClient = new NmiClient;
                $response = $nmiClient->scheduledSubscriptionPayment($transaction);
                if($this->status == 'Approved'){
                    $this->status = 'Active';
                    $this->transaction = 'Done';
                    $this->save();
                }
                $this->renewalSendMail($transaction);
                    // Return thank you page redirect
                return array(
                    'result' => 'success',
                );

            } catch (\Exception $e) {
                //wc_add_notice( sprintf( __( 'Gateway Error: %s', 'wc-nmi' ), $e->getMessage() ), 'error' );
                Log::channel('nmiPayments')->error(sprintf('Gateway Error: %s', $e->getMessage()));
                if($this->status == 'Approved'){
                    $this->status = 'Cancelled';
                    $this->save();
                }
                return [
                    'result' => 'failed',
                    'error_message' => $e->getMessage(),
                ];
            }
        }else{
            if($transaction->coupon_id!=null){
                $coupon = Coupon::find($transaction->coupon_id);
                if(($coupon->discount == 100 && $coupon->form == '%' || $transaction->total == 0) && $coupon->status == 'Active' && $coupon->renewal == 1){
                    $transaction->status = 'Completed';
                    $transaction->save();
                }
            }
        }
    }
    public function forceRenewalNmi(){
        $this->recurringNmi();
    }
    public function findLastTransaction()
    {
        $type = 0;
        if ($this->provider == 'paypal') {
            $type = 1;
        }

        if ($this->provider == 'nmi') {
            $type = 0;
        }

        return Transaction::whereCustomerId($this->customer_id)->whereType($type)->orderBy('done_date', 'DESC')->first();
    }
    public function findSubscriptions()
    {
        $subscriptions = self::all();
        $client = new \GuzzleHttp\Client();
        list($baseUrl, $clientId, $clientSecret) = PayPalClient::findKeys();
        foreach ($subscriptions as $subscription) {
            try {
                $res = $client->get($baseUrl . '/v1/billing/subscriptions/' . $subscription->subscription_id, [
                    'auth' => [$clientId, $clientSecret, 'basic'],
                    'headers' => [
                        'Accept-Language' => 'en_US',
                        'Accept' => 'application/json',
                    ],
                ]);
                $data = json_decode($res->getBody(), true);
                $cyles = 0;
                foreach ($data['billing_info']['cycle_executions'] as $record) {
                    $cyles += $record['cycles_completed'];
                }
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                echo \GuzzleHttp\Psr7\str($e->getRequest());
                if ($e->hasResponse()) {
                    echo \GuzzleHttp\Psr7\str($e->getResponse());
                }
                die;
            }
        }
    }
    public function analyzeSlug()
    {
        $paypalPlan = PaymentPlan::wherePlanId($this->plan_id)->first();
        if ($paypalPlan) {
            return $paypalPlan->analyzeSlug();
        }
        return null;
    }
    public function getEndDate($transaction=null)//with transaction
    {
        if($transaction == null){
            $subscription = Subscription::wherePaymentPlanId($this->plan_id)->first();
            if($subscription == null) return null;
            $transaction = $subscription->transaction;
            if($transaction == null )return null;
        }
        $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
        if($paymentSubscription){
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
            $intervalUnit = strtolower(env('INTERVAL_UNIT'));
            switch($provider){
                case 'nmi':
                    $cycles = $frequency;
                    $nextdatetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($transaction->done_date)) . " +$cycles $intervalUnit"));
                break;
                case 'paypal':
                    $paymentCycles = $paymentSubscription->getCycles();
                    $cycles = $frequency * $paymentCycles;
                    $nextdatetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($paymentSubscription->start_date)) . " +$cycles $intervalUnit"));
                break;
                case 'bank':
                    $cycles = $frequency;
                    $nextdatetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($paymentSubscription->start_date)) . " +$cycles $intervalUnit"));
                break;
            }
        }else{
            $nextdatetime = null;
        }
        return $nextdatetime;
    }
    public function nextPaymentAmount($coupon)
    {
        //service_id, frequency, coupon_id, is it first payment on the payment subscription?
        $paymentPlan = PaymentPlan::wherePlanId($this->plan_id)->first();
        if($paymentPlan){
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentPlan->analyzeSlug();
            $subscriptionPlan = SubscriptionPlan::find($planId);
            $amount = $subscriptionPlan->{'month_' . $frequency};
            $customer = Customer::find($customerId);
            $transaction = $this->findLastTransaction();
            return $customer->getPayAmount($amount,$coupon,$transaction);
        }else{
            Log::channel('nmiPayments')->error("Error payment plan not found $this->plan_id");
        }
    }
    public function findEndDate(){//without transaction
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $this->analyzeSlug();
        $intervalUnit = strtolower(env('INTERVAL_UNIT'));
        $cycles = Transaction::whereStatus('Completed')->wherePaymentSubscriptionId($this->subscription_id)->count();
        if($cycles>0)return date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($this->start_date)) . " +$cycles $intervalUnit"));
        return null;
    }
    public function getFrequency()
    {
        $paymentPlan = PaymentPlan::wherePlanId($this->plan_id)->first();
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentPlan->analyzeSlug();
        return $frequency;
    }
    public function updateSubscription($transaction = null)
    {
        if ($this->start_date <= date('Y-m-d H:i:s', strtotime('+1 hour'))) {
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $this->analyzeSlug();
            $plan = SubscriptionPlan::find($planId);
            $subscription = Subscription::whereCustomerId($customerId)->where(function ($query) use ($plan) {
                $query->whereHas('plan', function ($q) use ($plan) {
                    $q->whereServiceId($plan->service_id);
                });
            })->first();
            if ($subscription === null) {
                $subscription = new Subscription;
                $subscription->customer_id = $customerId;
                $customer = Customer::find($customerId);
                if ($couponId != null) {
                    $customer->coupon_id = $couponId;
                    $customer->save();
                }
            }else{
                $subscription->cancelled_date = null;
                $subscription->cancelled_reason = null;
                $subscription->cancelled_now = null;
                $subscription->end_date = null;
            }
            $subscription->payment_plan_id = $this->plan_id;
            $subscription->gateway = $provider;
            $subscription->plan_id = $planId;
            if($subscription->start_date==null)$subscription->start_date = $this->start_date;
            $intervalUnit = strtolower(env('INTERVAL_UNIT'));
            $subscription->end_date = null; //date("Y-m-d H:i:s",strtotime("+$frequency $intervalUnit",strtotime($this->start_date)));
            if ($couponId) {
                $subscription->coupon_id = $couponId;
                if ($transaction) {
                    $transaction->customer->changeCoupon($couponId);
                }
            }
            if ($transaction) {
                $subscription->transaction_id = $transaction->id;
                $customer = Customer::find($customerId);
                if ($customer->first_payment_date == null && $transaction->status == 'Completed' && $transaction->total>0) {
                    $customer->first_payment_date = date('Y-m-d', strtotime($transaction->done_date));
                    $customer->save();
                }
            }
            $subscription->frequency = $subscription->convertFrequencyString($frequency);
            $subscription->meta = $slug;
            $subscription->status = 'Active';
            $subscription->save();
            if ($this->status == 'Approved') {
                $this->status = 'Active';
                $this->transaction = 'Done';
                $this->save();
            }
            return true;
        }
        return false; // just now?
    }
    public function cancelChangedPaymentSubscriptions(){
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $this->analyzeSlug();
        $planId = $provider.'-'.$planId.'-'.$customerId;
        $paymentSubscriptions = PaymentSubscription::where('plan_id','like',"%$planId%")->whereTransaction('Changed')->whereStatus('Approved')->get();
        foreach($paymentSubscriptions as $paymentSubscription){
            $paymentSubscription->status = 'Cancelled';
            $paymentSubscription->save();
        }
    }
    private function postAction($action, $reason)
    {
        $result = false;
        switch($this->provider){
            case 'nmi':
                $result = true;
            break;
            case 'paypal':
                $client = new \GuzzleHttp\Client();
                list($baseUrl, $clientId, $clientSecret) = PayPalClient::findKeys();
                try {
                    $res = $client->post($baseUrl . '/v1/billing/subscriptions/' . $this->subscription_id . '/' . $action, [
                        'auth' => [$clientId, $clientSecret, 'basic'],
                        'headers' => [
                            'Accept-Language' => 'en_US',
                            'Accept' => 'application/json',
                        ],
                        'json' => [
                            'reason' => $reason,
                        ],
                    ]);
                    $data = json_decode($res->getBody(), true);
                    $result = true;
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    echo \GuzzleHttp\Psr7\str($e->getRequest());
                    if ($e->hasResponse()) {
                        echo \GuzzleHttp\Psr7\str($e->getResponse());
                    }
                }
            break;
        }
        return $result;
    }
    public function expireAction()
    {
        if ($this->postAction('expire', 'Fitemos expired due to customer action')) {
            $this->status = 'Expired';
            $this->save();
            return true;
        }
        return false;
    }
    public function cancelAction()
    {
        if ($this->postAction('cancel', 'Fitemos cancelled due to customer action')) {
            $this->status = 'Cancelled';
            $this->save();
            $this->customer->removeFriendShip();
            return true;
        }
        return false;
    }
    public function cancel()
    {
        $this->status = 'Cancelled';
        $this->save();
    }
    public function suspendAction()
    {
        if ($this->postAction('suspend', 'Fitemos suspended due to customer cancel action')) {
            $this->status = 'Suspended';
            $this->save();
        }
    }
    public function suspend()
    {
        $this->status = 'Suspended';
        $this->save();
    }
    public function renewalSendMail($transaction){
        $customer = $this->customer;
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $this->analyzeSlug();
        $plan = SubscriptionPlan::find($planId);
        $subscription = new Subscription;
        $frequencyString = $subscription->convertFrequencyString($frequency);
        $amount = $plan->{"month_$frequency"};
        if($couponId){
            $coupon = Coupon::find($couponId);
            if($coupon->renewal == 0){
                $coupon = null;    
            }
            $customer->changeCoupon($couponId);
        }else{
            $coupon = null;
        }
        $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
        $nextPaymentDate = date('d/m/Y',strtotime($paymentSubscription->getEndDate($transaction)));
        $nextPaymentTotal = $paymentSubscription->nextPaymentAmount($coupon);
        Mail::to($customer->email)->send(new RenewalPaymentNotification($customer->first_name,$frequencyString,$frequency,$amount,$transaction->total,$coupon,date('d/m/Y',strtotime($transaction->done_date)),$nextPaymentDate,$nextPaymentTotal));
    }
    public function sendFirstFreeMail($subscription){
        $customer = $this->customer;
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $this->analyzeSlug();
        if($customer==null){
            $customer = Customer::find($customerId);
        }
        $plan = SubscriptionPlan::find($planId);
        $frequencyString = $subscription->convertFrequencyString($frequency);
        if($couponId){
            $coupon = Coupon::find($couponId);
        }else{
            $coupon = null;
        }
        $cycles = $subscription->plan->free_duration;
        $nextPaymentDate = date('d/m/Y',strtotime($subscription->start_date." +$cycles day"));
        $nextPaymentTotal = $this->nextPaymentAmount($coupon);
        if( $customer->user )SendEmail::dispatch($customer,new VerifyMail($customer->user));
        NotifySubscriber::dispatch($customer,new \App\Mail\NotifySubscriber($customer))->delay(now()->addDays(7));
        $data = ['first_name'=>$customer->first_name,'last_name'=>$customer->last_name,'email'=>$customer->email,'gender'=>$customer->gender,'view_file'=>'emails.customers.create','subject'=>'Checkout Completed Customer for free trial'];
        Mail::to(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"))->queue(new MailQueue($data));
        $customer->sendFirstWorkout();
    }
    public function sendFirstMail($transaction, $sendableFirstWorkout=true, $bank=false){
        $customer = $this->customer;
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $this->analyzeSlug();
        $plan = SubscriptionPlan::find($planId);
        $subscription = new Subscription;
        $frequencyString = $subscription->convertFrequencyString($frequency);
        $amount = $plan->{"month_$frequency"};
        if($couponId){
            $coupon = Coupon::find($couponId);
        }else{
            $coupon = null;
        }
        $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
        $nextPaymentDate = date('d/m/Y',strtotime($paymentSubscription->getEndDate($transaction)));
        $nextPaymentTotal = $paymentSubscription->nextPaymentAmount($coupon);
        if($customer->user && $sendableFirstWorkout)SendEmail::dispatch($customer,new VerifyMail($customer->user));
        if(!$bank)SendEmail::dispatch($customer,new FirstPaymentNotification($customer->first_name,$frequencyString,$frequency,$amount,$transaction->total,$coupon,date('d/m/Y',strtotime($transaction->done_date)),$nextPaymentDate,$nextPaymentTotal));
        if($sendableFirstWorkout)NotifySubscriber::dispatch($customer,new \App\Mail\NotifySubscriber($customer))->delay(now()->addDays(7));
        $data = ['first_name'=>$customer->first_name,'last_name'=>$customer->last_name,'email'=>$customer->email,'gender'=>$customer->gender,'view_file'=>'emails.customers.create','subject'=>'Checkout Completed Customer for paid'];
        Mail::to(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"))->queue(new MailQueue($data));
        if($sendableFirstWorkout)$customer->sendFirstWorkout();
    }
}
