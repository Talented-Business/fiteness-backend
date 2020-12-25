<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class BankTransferRequest extends Model
{
    private $pageSize;
    private $statuses;
    private $pageNumber;
    private $from;
    private $to;
    private $customer_name;
    public $subscription_id;
    private $paymentPlan;
    protected $fillable = ['customer_id','plan_id','coupon_id','total','frequency'];
    public static function validateRules(){
        return [
            'service_id'=>'integer',
            'coupon_id'=>'integer | nullable',
            'frequency'=>'integer',
        ];
    }
    private static $searchableColumns = ['from','to','status','customer_name','customer_id'];
    public function customer(){
        return $this->belongsTo('App\Customer','customer_id');
    }
    public function plan(){
        return $this->belongsTo('App\SubscriptionPlan','plan_id');
    }
    public function transaction(){
        return $this->belongsTo('App\Transaction','transaction_id');
    }
    public function coupon(){
        return $this->belongsTo('App\Coupon','coupon_id');
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            if($request->input($property)!="" && $request->input($property)!=null && ($property=='status' && $request->input($property)!="all" || $property!='status') ){
                $this->{$property} = $request->input($property);
            }else{
            }
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function search(){
        $where = self::whereRaw('1');
        $index = true;
        if($this->from != null){
            $where->where('created_at','>=',$this->from.' 00:00:00');
            $index = false;
        }
        if($this->to != null){
            if($index)$where->orWhere('created_at','<=',$this->to.' 23:59:59');
            else $where->where('created_at','<=',$this->to.' 23:59:59');
            $index = false;
        }
        if($this->status != null){
            $where->where('status','=',$this->status);
        }
        if($this->customer_id != null){
            $where->where('customer_id','=',$this->customer_id);
        }
        if($this->customer_name!=null){
            $where->whereHas('customer', function($q){
                $q->where('first_name','like','%'.$this->customer_name.'%')->orWhere('last_name','like','%'.$this->customer_name.'%');
            });
        }
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('id', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $transaction){
            $items[$index]['customer_name'] = $transaction->customer->first_name.' '.$transaction->customer->last_name;
        }        
        return $response;
    }
    public function createTransaction(){
        if($this->transaction==null && $this->total>0){
            $subscription = Subscription::whereCustomerId($this->customer_id)->wherePlanId($this->plan_id)->first();
            if(!$subscription)$subscription = new Subscription;
            $subscription->frequency = $this->frequency;
            $servicePlan = SubscriptionPlan::find($this->plan_id);            
            $paymentPlan = PaymentPlan::createOrUpdate($servicePlan, $this->customer_id, $this->coupon, $subscription->convertMonths(),'bank');
            $paymentSubscription = new PaymentSubscription;
            $paymentSubscription->createWithBank($subscription, $paymentPlan);
            $transaction = new Transaction;
            $transaction->type = 2;//bank
            $transaction->content = "Bank Transaction ".$this->id;
            $transaction->customer_id = $this->customer_id;
            $transaction->plan_id = $this->plan_id;
            $transaction->coupon_id = $this->coupon_id;
            $transaction->payment_transaction_id = $this->id;
            $transaction->payment_subscription_id = $paymentSubscription->subscription_id;
            $transaction->total = $this->total;
            $transaction->done_date = date("Y-m-d H:i:s");
            $transaction->frequency = $this->frequency;
            $transaction->status = "Completed";
            $transaction->save();
            $transaction->createInvoice();
            $this->paymentPlan = $paymentPlan;
            return $transaction;
        }
        return false;
    }
    public function createOrUpdateSuscription(){
        if($this->transaction && $this->transaction->status == 'Completed'){
            $servicePlan = SubscriptionPlan::find($this->plan_id);            
            $subscription = Subscription::whereCustomerId($this->customer_id)->first();
            if(!$subscription){
                $subscription = new Subscription;
                $subscription->plan_id = $this->plan_id;
                $subscription->customer_id = $this->customer_id;
                $subscription->coupon_id = $this->coupon_id;
                $subscription->start_date = date('Y-m-d H:i:s');
	        $paymentSubscription = PaymentSubscription::whereSubscriptionId($this->transaction->payment_subscription_id)->first();
            }else{
                if($subscription->status == 'Cancelled'){
                    $subscription->cancelled_now = null;
                }
                $subscription->end_date = null;
            }
            $subscription->transaction_id = $this->transaction_id;
            $subscription->gateway = 'bank';
            $subscription->frequency = $this->frequency;
            $subscription->meta = PaymentPlan::createSlug($servicePlan, $this->customer_id, $this->coupon, $subscription->convertMonths($this->frequency),$subscription->gateway);
            $subscription->status = 'Active';
            $subscription->reminder_before_seven = 0;
            $subscription->reminder_before_one = 0;
            $subscription->reminder_after_one = 0;
            $subscription->payment_plan_id = $this->paymentPlan->plan_id;
            $subscription->save();
            if(isset($paymentSubscription))$paymentSubscription->sendFirstMail($this->transaction, true, true);
        }
    }
}