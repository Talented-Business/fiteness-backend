<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use App\Mail\NmiVault;
use App\Mail\SubscriptionCancel;
use App\Mail\SubscriptionCancelAdmin;
use Illuminate\Support\Facades\DB;
use Mail;

class Subscription extends Model
{
    private $pageSize;
    private $statuses;
    private $pageNumber;
    private $from;
    private $to;
    private $search;
    private $firstWorkoutStartDate;
    const SUBSCRIPTION_URL = 'https://www.fitemos.com/pricing';
    public static function validateRules()
    {
        return array(
        );
    }
    private static $searchableColumns = ['from', 'to', 'plan_id', 'search', 'status'];
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public function coupon()
    {
        return $this->belongsTo('App\Coupon', 'coupon_id');
    }
    public function transaction()
    {
        return $this->belongsTo('App\Transaction', 'transaction_id');
    }
    public function plan()
    {
        return $this->belongsTo('App\SubscriptionPlan', 'plan_id');
    }
    public function search()
    {
        $where = Subscription::where(function ($query) {
            if ($this->search != null) {
                $query->whereHas('customer', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            }
        });
        $index = false;
        if ($this->from != null) {
            $where->where('start_date', '>=', $this->from);
            $index = true;
        }
        if ($this->to != null) {
            if ($index) {
                $where->orWhere('end_date', '<=', $this->to);
            } else {
                $where->where('end_date', '<=', $this->to);
            }
        }
        if ($this->plan_id != null) {
            $where->where('plan_id', '=', $this->plan_id);
        }
        if ($this->status != null) {
            switch($this->status){
                case 'Active':
                    $where->whereStatus($this->status)->whereNull('end_date');
                break;
                case 'Leaving':
                    $where->whereStatus('Active')->whereNotNull('end_date');
                break;
                default:
                $where->where('status', '=', $this->status);
            } 
        }
        $currentPage = $this->pageNumber + 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        $response = $where->orderBy('id', 'ASC')->paginate($this->pageSize);
        $items = $response->items();
        foreach ($items as $index => $subscription) {
            $items[$index]['customer_name'] = $subscription->customer->first_name . ' ' . $subscription->customer->last_name;
            $items[$index]['subscription_name'] = $subscription->plan->service->title;
            $transactions = Transaction::wherePlanId($subscription->plan_id)->whereCustomerId($subscription->customer_id)->whereStatus('Completed')->get();
            $totalPaid = 0;
            foreach($transactions as $transaction){
                $totalPaid += $transaction->total;
            }
            if($subscription->end_date && $subscription->status == 'Active'){
                $items[$index]['status'] = 'LEAVING';
            }
            $items[$index]['total_paid'] = round($totalPaid,2);
        }
        return $response;
    }
    function extends () {
        $this->plan; //active_time,total_paid, monthly_cost
        $dayDiff = 0;
        $monthDiff = 0;
        $transactions = Transaction::wherePlanId($this->plan_id)->whereCustomerId($this->customer_id)->whereStatus('Completed')->get();
        $totalPaid = 0;
        foreach($transactions as $transaction){
            $totalPaid += $transaction->total;
        }
        $this['total_paid'] = round($totalPaid,2);
        $this['monthly_cost'] = 0;
        switch ($this->frequency) {
            case 'Mensual':
                if($this->transaction)$this['monthly_cost'] = $this->transaction->total;
                $this['frequency_name'] = '1 month';
                $monthDiff = 1;
                break;
            case 'Trimestral':
                if($this->transaction)$this['monthly_cost'] = round($this->transaction->total / 3, 2);
                $this['frequency_name'] = '3 months';
                $monthDiff = 3;
                break;
            case 'Semestral':
                if($this->transaction)$this['monthly_cost'] = round($this->transaction->total / 6, 2);
                $this['frequency_name'] = '6 months';
                $monthDiff = 6;
                break;
            case 'Anual':
                if($this->transaction)$this['monthly_cost'] = round($this->transaction->total / 12, 2);
                $this['frequency_name'] = '12 months';
                $monthDiff = 12;
                break;
            case 'Once':
                $this['monthly_cost'] = 0;
                $this['frequency_name'] = '7 days';
                $dayDiff = 7;
                break;
        }
        $dateDiff = "";
        if ($this->status == "Active") {
            $dateDiff = round((strtotime($this->end_date) - strtotime(date('Y-m-d'))) / 3600 / 24);
            if ($dateDiff < 0) {
                $dateDiff = "";
            } else {
                $dateDiff .= " days";
            }

        }
        $this['active_time'] = $dateDiff;
        $this['service_id'] = $this->plan->service_id;
        $this['service_name'] = $this->plan->service->title;
    }
    public function searchAll()
    {
        $where = Subscription::where(function ($query) {
            if ($this->search != null) {
                $query->whereHas('customer', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')->where('last_name', 'like', '%' . $this->search . '%');
                });
            }
        });
        $index = true;
        if ($this->from != null) {
            $where->Where('start_date', '>=', $this->from);
            $index = false;
        }
        if ($this->to != null) {
            if ($index) {
                $where->orWhere('end_date', '<=', $this->to);
            } else {
                $where->Where('end_date', '<=', $this->to);
            }

            $index = false;
        }
        if ($this->plan_id != null) {
            if ($index) {
                $where->orWhere('plan_id', '=', $this->type);
            } else {
                $where->Where('plan_id', '=', $this->type);
            }

            $index = false;
        }
        if ($this->status != null) {
            switch($this->status){
                case 'Active':
                    $where->whereStatus($this->status)->whereNull('end_date');
                break;
                case 'Leaving':
                    $where->whereStatus('Active')->whereNotNull('end_date');
                break;
                default:
                $where->where('status', '=', $this->status);
            } 
            $index = false;
        }
        return $where->get();
    }
    public function assignSearch($request)
    {
        foreach (self::$searchableColumns as $property) {
            if ($request->input($property) != "" && $request->input($property) != null && ($property == 'plan_id' && $request->input($property) != "all" || $property == 'status' && $request->input($property) != "all" || $property != 'plan_id' && $property != "status")) {
                $this->{$property} = $request->input($property);
            } else {
            }
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function nextPaymentTime()
    {
        if($this->status == 'Cancelled'){
            return null;
        }
        if ($this->transaction && $this->transaction->status=='Completed') {
            $paymentSubscription = PaymentSubscription::whereSubscriptionId($this->transaction->payment_subscription_id)->first();
            if ($paymentSubscription) {
                return $paymentSubscription->getEndDate($this->transaction);
            }
        }else{
            $paypalPlan = PaymentPlan::wherePlanId($this->payment_plan_id)->first();
            if ($paypalPlan) {
                list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paypalPlan->analyzeSlug();
                $intervalUnit = strtolower(env('INTERVAL_UNIT'));
                $cycles = $frequency;
                $nextdatetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($this->start_date)) . " +$cycles $intervalUnit"));
                return $nextdatetime;
            }            
        }
        return null;
    }
    public static function convertIsoDateToString($time)
    {
        if (gettype($time) == 'string') {
            $time = strtotime($time);
        }
        return date('Y-m-d\TH:i:s\Z', $time - date('Z'));
    }
    private static function findTransactions() //only transaction
    {
        $subscriptions = PaymentSubscription::whereTransaction('Changed')->get();
        $endTime = Subscription::convertIsoDateToString(time());
        $startTime = Subscription::convertIsoDateToString(time() - 24 * 3600 * 3);
        $client = new \GuzzleHttp\Client();
        foreach ($subscriptions as $paymentSubscription) {
            if($paymentSubscription->provider != 'paypal')continue;
            list($baseUrl, $clientId, $clientSecret) = PayPalClient::findKeys();
            try {
                $res = $client->get($baseUrl . '/v1/billing/subscriptions/' . $paymentSubscription->subscription_id . '/transactions?start_time=' . $startTime . '&end_time=' . $endTime, [
                    'auth' => [$clientId, $clientSecret, 'basic'],
                    'headers' => [
                        'Accept-Language' => 'en_US',
                        'Accept' => 'application/json',
                    ],
                ]);
                $records = json_decode($res->getBody(), true);
                $paypalPlan = PaymentPlan::wherePlanId($paymentSubscription->plan_id)->first();
                $values = explode('-', $paypalPlan->slug);
                $provider = $values[0];
                $planId = $values[1];
                $customerId = $values[2];
                $frequency = $values[3];
                if (isset($values[4])) {
                    $couponId = $values[4];
                } else {
                    $couponId = null;
                }
                if (isset($records['transactions'])) {
                    foreach ($records['transactions'] as $record) {
                        $transaction = Transaction::wherePaymentTransactionId($record['id'])->first();
                        if ($transaction === null) {
                            $transaction = new Transaction;
                        }

                        $transaction->type = 1; //paypal
                        $transaction->payment_transaction_id = $record['id'];
                        $transaction->payment_subscription_id = $paymentSubscription->subscription_id;
                        $transaction->total = $record['amount_with_breakdown']['gross_amount']['value'];
                        $transaction->done_date = date('Y-m-d H:i:s', strtotime($record['time']));
                        switch ($record['status']) {
                            case 'COMPLETED':
                                $transaction->status = 'Completed';
                                $transaction->createInvoice();
                                break;
                            case 'PENDING':
                                $transaction->status = 'Pending';
                                break;
                            default:
                                $transaction->status = 'Declined';
                        }
                        $transaction->content = 'PayPal Transaction';
                        $transaction->customer_id = $customerId;
                        $transaction->plan_id = $planId;
                        if ($couponId) {
                            $transaction->coupon_id = $couponId;
                        }

                        $s = new Subscription;
                        $transaction->frequency = $s->findFrequency($frequency);
                        $transaction->save();
                        //print_r($record['id']);
                    }
                }
                $paymentSubscription->transaction = 'Done';
                $paymentSubscription->save();
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                echo \GuzzleHttp\Psr7\str($e->getRequest());
                //echo $e->getMessage();
                if ($e->hasResponse()) {
                    echo \GuzzleHttp\Psr7\str($e->getResponse());
                }
            }
        }
    }
    public function findAllTransactionsWithPayPal(){
        $paymentSubscriptions = PaymentSubscription::whereCustomerId($this->customer_id)->orderBy('start_date', 'DESC')->get();
        $client = new \GuzzleHttp\Client();
        foreach($paymentSubscriptions as $paymentSubscription){
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
            if($provider=='paypal' && $paymentSubscription->status == 'Active'){
                $startTime = $paymentSubscription->start_time;
                $endTime = Subscription::convertIsoDateToString(time());
                list($baseUrl, $clientId, $clientSecret) = PayPalClient::findKeys();
                try {
                    $res = $client->get($baseUrl . '/v1/billing/subscriptions/' . $paymentSubscription->subscription_id . '/transactions?start_time=' . $startTime . '&end_time=' . $endTime, [
                        'auth' => [$clientId, $clientSecret, 'basic'],
                        'headers' => [
                            'Accept-Language' => 'en_US',
                            'Accept' => 'application/json',
                        ],
                    ]);
                    $records = json_decode($res->getBody(), true);
                    if (isset($records['transactions'])) {
                        foreach ($records['transactions'] as $record) {
                            $transaction = Transaction::wherePaymentTransactionId($record['id'])->first();
                            if ($transaction === null) {
                                $transaction = new Transaction;
                            }
    
                            $transaction->type = 1; //paypal
                            $transaction->payment_transaction_id = $record['id'];
                            $transaction->payment_subscription_id = $paymentSubscription->subscription_id;
                            $transaction->total = $record['amount_with_breakdown']['gross_amount']['value'];
                            $transaction->done_date = date('Y-m-d H:i:s', strtotime($record['time']));
                            switch ($record['status']) {
                                case 'COMPLETED':
                                    $transaction->status = 'Completed';
                                    $transaction->createInvoice();
                                    break;
                                case 'PENDING':
                                    $transaction->status = 'Pending';
                                    break;
                                default:
                                    $transaction->status = 'Declined';
                            }
                            $transaction->content = 'PayPal Transaction';
                            $transaction->customer_id = $customerId;
                            $transaction->plan_id = $planId;
                            if ($couponId) {
                                $transaction->coupon_id = $couponId;
                            }
    
                            $s = new Subscription;
                            $transaction->frequency = $s->findFrequency($frequency);
                            $transaction->save();
                            //print_r($record['id']);
                        }
                    }
                    $paymentSubscription->save();
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    echo \GuzzleHttp\Psr7\str($e->getRequest());
                    //echo $e->getMessage();
                    if ($e->hasResponse()) {
                        echo \GuzzleHttp\Psr7\str($e->getResponse());
                    }
                }                    
            }
        }
    }
    private function findFrequency($frequency)
    {
        $string = 'Mensual';
        switch ($frequency) {
            case 1:
                $string = 'Mensual';
                break;
            case 3:
                $string = 'Trimestral';
                break;
            case 6:
                $string = 'Semestral';
                break;
            case 12:
                $string = 'Anual';
                break;
        }
        return $string;
    }
    private function findLastPaymentSubscription($paymentSubscriptions)
    {
        $intervalUnit = strtolower(env('INTERVAL_UNIT'));
        $startDate = null;
        $endDate = null;
        $lastPaymentSubscription = null;
        $lastTransaction = null;
        //calculate start date and end date.
        foreach ($paymentSubscriptions as $paymentSubscription) {
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
            if ($this->plan_id == $planId) {
                // if end date past than today the system suspended to cancelled
                if ($paymentSubscription->status == 'Suspended') {
                    $paymentCycles = $paymentSubscription->getCycles();
                    $cycles = $frequency * $paymentCycles;
                    $paymentSubscriptionEndDate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($paymentSubscription->start_date)) . " +$cycles $intervalUnit"));
                    if ($paymentSubscriptionEndDate < date("Y-m-d H:i:s")) {
                        $paymentSubscription->cancelAction();
                    }
                }
                if ($paymentSubscription->status == 'Active' || $paymentSubscription->status == 'Suspended') {
                    if ($startDate == null) {
                        $startDate = $paymentSubscription->start_date;
                    } else {
                        if ($startDate < $paymentSubscription->start_date) {
                            $startDate = $paymentSubscription->start_date;
                        }
                    }
                }
                if ($lastPaymentSubscription == null) {
                    $lastPaymentSubscription = $paymentSubscription;
                    if ($lastPaymentSubscription->status != 'Active' ) {
                        switch($provider){
                            case 'nmi':
                            break;
                            case 'paypal':
                                $paymentCycles = $lastPaymentSubscription->getCycles();
                                $cycles = $frequency * $paymentCycles;
                                $endDate = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($lastPaymentSubscription->start_date)) . " +$cycles $intervalUnit"));
                            break;
                        }
                    }
                    
                    $lastTransaction = $lastPaymentSubscription->findLastTransaction();
                    
                }
            }
        }
        return [$lastPaymentSubscription, $lastTransaction, $startDate, $endDate];
    }
    private function renewalProcessing($paymentSubscriptions, $lastPaymentSubscription) 
    {//by provider autopayment
        foreach ($paymentSubscriptions as $paymentSubscription) {
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
            if ($this->plan_id == $planId) {
                if ($paymentSubscription->status === 'Suspended' && $paymentSubscription->id != $lastPaymentSubscription->id) {
                    $endDate = $paymentSubscription->getEndDate();
                    if ($endDate <= date('Y-m-d H:i:s')) {
                        $paymentSubscription->cancelAction();
                    }
                }
                if ($paymentSubscription->status === 'Active' && $paymentSubscription->id != $lastPaymentSubscription->id) {
                    $paymentSubscription->suspendAction();
                }
            }
        }
        if ($lastPaymentSubscription->status == 'Active' || $lastPaymentSubscription->status == 'Approved') {
            // with customer vault nmi
            $lastPaymentSubscription->recurringProcessingWithCustomerVault($this);
        }
    }
    private function scrapingPaid()
    {
        $paymentSubscriptions = PaymentSubscription::whereCustomerId($this->customer_id)->orderBy('start_date', 'DESC')->get();
        //find all payment subscriptions for every customer from now such as paypal
        list($lastPaymentSubscription, $lastTransaction, $startDate, $endDate) = $this->findLastPaymentSubscription($paymentSubscriptions);
        if ($lastPaymentSubscription) {
            $this->renewalProcessing($paymentSubscriptions, $lastPaymentSubscription);
            list($lastPaymentSubscription, $lastTransaction, $startDate, $endDate) = $this->findLastPaymentSubscription($paymentSubscriptions);
        }

        if ($lastPaymentSubscription && $lastTransaction) {
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $lastPaymentSubscription->analyzeSlug();
            if ($this->transaction_id != $lastTransaction->id || $this->status == 'Pending-Cancellation' || $this->status == 'Pending') {
                //according to last subscription, update start date and end date, status and so on.
                $this->massSave($provider, $planId, $customerId, $couponId, $frequency, $lastPaymentSubscription->plan_id, $startDate, $endDate, $lastTransaction, $lastPaymentSubscription->status);
            } else {
                $endDate = $lastPaymentSubscription->getEndDate();
                $changed = false;
                switch ($lastPaymentSubscription->status) {
                    case 'Cancelled':
                        if ($this->end_date === null) {
                            $this->end_date = $endDate;
                            $changed = true;
                        }
                        if ($this->status !== 'Cancelled' && date("Y-m-d H:i:s")>=$this->end_date) {
                            $this->status = 'Cancelled';
                            $changed = true;
                        }
                        break;
                    case 'Active':
                        break;    
                }
                print_r($endDate);
                print_r("\n");
                if ($changed) {
                    print_r('changed');
                    $this->save();
                }
            }
        }
    }
    private function massSave($provider, $planId, $customerId, $couponId, $frequency, $paymentPlanId, $startDate, $endDate, $lastTransaction, $status)
    {
        switch($lastTransaction->status){
            case 'Completed':
                $this->gateway = $provider;
                $this->plan_id = $planId;
                $this->customer_id = $customerId;
                if ($couponId) {
                    $this->coupon_id = $couponId;
                }
                $this->frequency = $this->findFrequency($frequency);
                $this->payment_plan_id = $paymentPlanId;
                $this->start_date = $startDate;
                //$this->end_date = $endDate;
                if ($lastTransaction) {
                    $this->transaction_id = $lastTransaction->id;
                    $customer = Customer::find($customerId);
                    if ($customer->first_payment_date == null && $lastTransaction->total>0) {
                        $customer->first_payment_date = date('Y-m-d', strtotime($lastTransaction->done_date));
                        $customer->save();
                    }
                }
        
                switch ($status) {
                    case 'Active':
                        $this->status = 'Active';
                        break;
                    case 'Suspended':
                        $this->status = 'Hold-on';
                        break;
                    case 'Cancelled':
                        $this->status = 'Cancelled';
                        break;
                    case 'Expired':
                        $this->status = 'Expired';
                        break;
                    case 'Approved':
                        $this->status = 'Pending';
                        break;
                    case 'Approval_pending':
                        $this->status = 'Pending';
                        break;
                }
                $this->save();
                    break;
            case 'Pending':
            break;
            case 'Declined':
                if($provider=='nmi'){
                    $paymentSubscription = PaymentSubscription::whereSubscriptionId($lastTransaction->payment_subscription_id)->first();
                    $paymentSubscription->status = 'Cancelled';
                    $paymentSubscription->save();
                    $this->status = 'Cancelled';
                    $this->transaction_id = $lastTransaction->id;
                    $this->save();
                }
            break;
        }
    }
    private function scraping()
    {
        //isfree
        if ($this->plan && $this->plan->type == 'Free') {
            $t1 = time();
            $t2 = strtotime($this->end_date);
            $diff = $t1 - $t2;
            $hours = $diff / 3600;
            if ($hours > 72) {
                $coupon = Coupon::whereType('Private')->whereCustomerId($this->customer_id)->whereCode(Coupon::TRIAL_AFTER . '_' . $this->plan->service_id . '_' . $this->customer_id)->first();
                if ($coupon === null) {
                    $coupon = new Coupon;
                    $coupon->generatePrivateTrialAfter($this);
                }
            } else if ($hours > -24) {
                $coupon = Coupon::whereType('Private')->whereCustomerId($this->customer_id)->whereCode(Coupon::TRIAL_BEFORE . '_' . $this->plan->service_id . '_' . $this->customer_id)->first();
                if ($coupon === null) {
                    $coupon = new Coupon;
                    $coupon->generatePrivateTrialBefore($this);
                }
            }
            if ($this->end_date < date('Y-m-d H:i:s') && $this->status == 'Active') {
                $this->status = 'Expired';
                $this->save();
            }
        } else {
            $this->scrapingPaid();
        }
    }
    private static function checkStatus()
    {
        $customers = Customer::where(function ($query) {
            $query->whereHas('user', function ($q) {
                $q->where('active', '=', 1);
            });
        })->get();
        $services = Service::all();
        foreach ($services as $service) {
            foreach ($customers as $customer) { //I didn't consider service_id
                $subscription = Subscription::whereCustomerId($customer->id)->where(function ($query) use ($service) {
                    $query->whereHas('plan', function ($q) use ($service) {
                        $q->whereServiceId($service->id);
                    });
                })->first();
                if ($subscription) {
                    $subscription->scraping();
                } else {
                    //new creation if payment subscription exist
                    $paymentSubscription = PaymentSubscription::whereCustomerId($customer->id)->first();
                    if ($paymentSubscription) {
                        $subscription = new Subscription;
                        $subscription->generate($paymentSubscription);
                    }
                }
            }
        }
    }
    private function generate($paymentSubscription)
    {
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
        $lastTransaction = $paymentSubscription->findLastTransaction();
        $this->massSave($provider, $planId, $customerId, $couponId, $frequency, $paymentSubscription->plan_id, $startDate, $endDate, $lastTransaction, $paymentSubscription->status);
    }
    public static function scrape()
    {
        //find transations
        Subscription::findTransactions();
        //check subscriptions status
        Subscription::checkStatus();
        //Coupon scrape
        Coupon::scrape();
    }
    public function cancel($enableEnd, $reason,$credit)
    {
        if ($this->plan->type === 'Free') {
            if ($enableEnd === "yes") {
                $this->cancelled_date = date('Y-m-d H:i:s');
            } else {
                $this->cancelled_date = date('Y-m-d H:i:s');
                $this->end_date = date('Y-m-d H:i:s');
            }
            $this->cancelled_reason = $reason;
            $this->cancelled_now = $enableEnd;
            $this->status = 'Cancelled';
            $this->save();
            return true;
        } else { //paid
            $paymentSubscription = PaymentSubscription::whereSubscriptionId($this->transaction->payment_subscription_id)->first();
            if ($paymentSubscription) {
                if ($paymentSubscription->cancelAction()){
                    if ($enableEnd === "yes") {
                        $this->cancelled_date = date('Y-m-d H:i:s');
                        $this->end_date = $paymentSubscription->getEndDate();
                    } else {
                        $this->cancelled_date = date('Y-m-d H:i:s');
                        $this->end_date = date('Y-m-d H:i:s');
                        $this->status = 'Cancelled';
                    }
                    $this->cancelled_reason = $reason;
                    $this->cancelled_now = $enableEnd;
                    $this->save();
                    if($credit)$this->customer->removePaymentMethods();
                    setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
                    $cancelDate = iconv('ISO-8859-2', 'UTF-8', strftime("%d de %B del %Y", strtotime($this->cancelled_date)));
                    $subscriptionEndDate = iconv('ISO-8859-2', 'UTF-8', strftime("%d de %B del %Y", strtotime($this->end_date)));
                    Mail::to($this->customer->email)->send(new SubscriptionCancel($this->customer->first_name,$this->frequency,$cancelDate,$subscriptionEndDate));                    
                    Mail::to(env("MAIL_FROM_ADDRESS"))->send(new SubscriptionCancelAdmin($this->customer,$this->frequency,$cancelDate,$reason,$enableEnd));
                    return true;
                }
            }
        }
        return false;
    }
    public function suspendWithNmi(){
        $paymentSubscription = PaymentSubscription::whereSubscriptionId($this->transaction->payment_subscription_id)->first();
        if ($paymentSubscription) {
            if ($paymentSubscription->suspendAction()){
                $this->cancelled_date = date('Y-m-d H:i:s');
                $this->end_date = $paymentSubscription->getEndDate();
                $this->save();
                return true;
            }
        }
}
    public function convertFrequency()
    {
        switch ($this->frequency) {
            case 'Mensual':
                return 'monthly';
                break;
            case 'Trimestral':
                return 'quarterly';
                break;
            case 'Semestral':
                return 'semiannual';
                break;
            case 'Anual':
                return 'yearly';
                break;
        }
        return '';
    }
    public function nextWorkoutPlan(){
        $lastPaymentSubscription = $this->getLastPaymentSubscription();
        if($lastPaymentSubscription){
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $lastPaymentSubscription->analyzeSlug();
            switch ($frequency) {
                case '1':
                    return 'monthly';
                    break;
                case '3':
                    return 'quarterly';
                    break;
                case '6':
                    return 'semiannual';
                    break;
                case '12':
                    return 'yearly';
                    break;
            }
        }
        return '';
    }
    private function getLastPaymentSubscription(){
        $paymentSubscriptions = PaymentSubscription::whereCustomerId($this->customer_id)->orderBy('start_date', 'DESC')->get();
        $lastPaymentSubscription = null;
        foreach($paymentSubscriptions as $paymentSubscription){
            list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
            if($planId == $this->plan_id){
                $lastPaymentSubscription = $paymentSubscription;
                break;
            }            
        }
        return $lastPaymentSubscription;
    }
    public function convertMonths()
    {
        switch ($this->frequency) {
            case 'Mensual':
                return 1;
                break;
            case 'Trimestral':
                return 3;
                break;
            case 'Semestral':
                return 6;
                break;
            case 'Anual':
                return 12;
                break;
        }
        return 1;
    }
    public function convertFrequencyString($frequency)
    {
        switch ($frequency) {
            case 1:case '1':
                return 'Mensual';
                break;
            case 3:case '3':
                return 'Trimestral';
                break;
            case 6:case '6':
                return 'Semestral';
                break;
            case 12:case '12':
                return 'Anual';
                break;
        }
        return null;
    }
    public function currentWorkoutPeriod(){
        if($this->transaction){
            $startDate = iconv('ISO-8859-2', 'UTF-8', strftime("%d/%B/%Y", strtotime($this->transaction->done_date)));
            $endDate = iconv('ISO-8859-2', 'UTF-8', strftime("%d/%B/%Y", strtotime($this->nextPaymentTime())));
            return $this->frequency.' ('.$startDate.' - '.$endDate.')';
        }
        return '';
    }
    public function renewalOptions(){
        $nextPaymentTime = $this->nextPaymentTime();
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        if($nextPaymentTime)$nextPaymentTime = strtotime($nextPaymentTime);
        else $nextPaymentTime = time();
        $texts = [];
        $textsWithPrice = [];
        $nextRenewalStartDate = iconv('ISO-8859-2', 'UTF-8', strftime("%d/%B/%Y", $nextPaymentTime));
        $intervalUnit = strtolower(env('INTERVAL_UNIT'));
        if($this->plan->month_1){
            $cycles = 1;
            $nextPaymentEndTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", $nextPaymentTime) . " +$cycles $intervalUnit"));
            $nextRenewalEndDate = iconv('ISO-8859-2', 'UTF-8', strftime("%d/%B/%Y", strtotime($nextPaymentEndTime)));
            $texts['monthly'] = 'Mensual ('.$nextRenewalStartDate.' - '.$nextRenewalEndDate.')';
            $textsWithPrice[] = ['frequency'=>'monthly','label' => 'Mensual - '.$this->plan->month_1.' ('.$nextRenewalStartDate.' - '.$nextRenewalEndDate.')'];
        }
        if($this->plan->month_3){
            $cycles = 3;
            $nextPaymentEndTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", $nextPaymentTime) . " +$cycles $intervalUnit"));
            $nextRenewalEndDate = iconv('ISO-8859-2', 'UTF-8', strftime("%d/%B/%Y", strtotime($nextPaymentEndTime)));
            $texts['quarterly'] = 'Trimestral ('.$nextRenewalStartDate.' - '.$nextRenewalEndDate.')';
            $textsWithPrice[] = ['frequency'=>'quarterly','label' => 'Trimestral - '.$this->plan->month_3.' ('.$nextRenewalStartDate.' - '.$nextRenewalEndDate.')'];
        }
        if($this->plan->month_6){
            $cycles = 6;
            $nextPaymentEndTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", $nextPaymentTime) . " +$cycles $intervalUnit"));
            $nextRenewalEndDate = iconv('ISO-8859-2', 'UTF-8', strftime("%d/%B/%Y", strtotime($nextPaymentEndTime)));
            $texts['semiannual'] = 'Semestral ('.$nextRenewalStartDate.' - '.$nextRenewalEndDate.')';
            $textsWithPrice[] = ['frequency'=>'semiannual','label' => 'Semestral - '.$this->plan->month_6.' ('.$nextRenewalStartDate.' - '.$nextRenewalEndDate.')'];
        }
        if($this->plan->month_12){
            $cycles = 12;
            $nextPaymentEndTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", $nextPaymentTime) . " +$cycles $intervalUnit"));
            $nextRenewalEndDate = iconv('ISO-8859-2', 'UTF-8', strftime("%d/%B/%Y", strtotime($nextPaymentEndTime)));
            $texts['yearly'] = 'Anual ('.$nextRenewalStartDate.' - '.$nextRenewalEndDate.')';
            $textsWithPrice[] = ['frequency'=>'yearly','label' => 'Anual - '.$this->plan->month_12.' ('.$nextRenewalStartDate.' - '.$nextRenewalEndDate.')'];
        }
        return ['text'=>$texts,'price'=>$textsWithPrice];
    }
    public static function findOrCreate($customerId, $servicePlan, $coupon, $frequency, $gateway)
    {
        if($coupon&&$coupon->validate($customerId) === false)$coupon = null;
        $subscription = Subscription::whereCustomerId($customerId)->where(function ($query) use ($servicePlan) {
            $query->whereHas('plan', function ($q) use ($servicePlan) {
                $q->whereServiceId($servicePlan->service_id);
            });
        })->first();
        if ($subscription === null) {
            $subscription = new Subscription;
            $subscription->customer_id = $customerId;
            $subscription->plan_id = $servicePlan->id;
            if ($coupon) {
                $subscription->coupon_id = $coupon->id;
            }

            $subscription->gateway = $gateway;
            $subscription->frequency = $subscription->convertFrequencyString($frequency);
            $subscription->status = 'Pending';
            $subscription->save();
        }
        return $subscription;
    }
    public static function sendMails(){//nmi suspended expired notify email
        $subscriptions  = Subscription::whereStatus('Active')->get();
        foreach($subscriptions as $subscription){
            if($subscription->transaction){
                $currentPaymentSubscription = PaymentSubscription::whereSubscriptionId($subscription->transaction->payment_subscription_id)->first();
            }else{
                $currentPaymentSubscription = null;
            }
            if($subscription->customer->nmi_vault_id == null){
                $lastPaymentSubscription = $subscription->getLastPaymentSubscription();
                if($lastPaymentSubscription && $currentPaymentSubscription ){
                    if($lastPaymentSubscription->provider == 'nmi' && ($lastPaymentSubscription->status == 'Suspended' || $lastPaymentSubscription->status == 'Approved')){
                        $endDate = $lastPaymentSubscription->getEndDate();
                        $now = time();
                        $threeDaysAgo = $now - 3*24*3600;
                        $twoDaysAgo = $now - 2*24*3600;
                        if($threeDaysAgo<strtotime($endDate) && $twoDaysAgo>strtotime($endDate)){
                            Mail::to($subscription->customer->email)->send(new NmiVault($subscription->customer->first_name,self::SUBSCRIPTION_URL));
                        }
                    }
                }
            }
        }
    }
    private function initailFirstWorkoutStartDate(){
        $record = $paymentSubscription = DB::table('payment_subscriptions')
        ->join('transactions','payment_subscriptions.subscription_id','=','transactions.payment_subscription_id')
        ->join('subscription_plans','subscription_plans.id','=','transactions.plan_id')
        ->select('payment_subscriptions.start_date')
        ->where('transactions.status','=','Completed')
        ->where('subscription_plans.service_id','=','1')
        ->where('payment_subscriptions.customer_id','=',$this->customer_id)
        ->orderBy('payment_subscriptions.start_date', 'asc')
        ->first();
        if($record){
            $this->firstWorkoutStartDate = $record->start_date;
        }
    }
    public function getFirstWorkoutStartDate(){//workout
        if($this->firstWorkoutStartDate==null){
            $this->initailFirstWorkoutStartDate();
        }
        return $this->firstWorkoutStartDate;
    }
    public function isNewWeek($currentDate = null){
        if($currentDate == null){
            $currentDate = date('Y-m-d');
        }
        if($this->firstWorkoutStartDate==null){
            $this->initailFirstWorkoutStartDate();
        }
        if($this->firstWorkoutStartDate){
            $week = date('w',strtotime($this->firstWorkoutStartDate));
            if($week == 0){
                $addDays = 1;
            }else{
                $addDays = 8-$week;
            }
            if(date('Y-m-d',strtotime($this->firstWorkoutStartDate)+$addDays*3600*24) >$currentDate){
                return true;
            }
            return false;
        }
        return false;
    }
}
