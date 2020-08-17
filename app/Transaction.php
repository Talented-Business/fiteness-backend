<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Transaction extends Model
{
    private $pageSize;
    private $statuses;
    private $pageNumber;
    private $from;
    private $to;
    private $customer_name;
    public $subscription_id;
    public static function validateRules(){
        return array(
        );
    }
    private static $searchableColumns = ['from','to','type','customer_name','customer_id','subscription_id'];
    public function customer(){
        return $this->belongsTo('App\Customer','customer_id');
    }
    public function plan(){
        return $this->belongsTo('App\SubscriptionPlan','plan_id');
    }
    public function invoice(){
        return $this->hasOne('App\Invoice','transaction_id');
    }
    public function search(){
        $where = Transaction::where(function($query){
            if($this->customer_name!=null){
                $query->whereHas('customer', function($q){
                    $q->where('first_name','like','%'.$this->customer_name.'%')->orWhere('last_name','like','%'.$this->customer_name.'%');
                });
            }
        });
        $index = true;
        if($this->from != null){
            $where->Where('created_at','>=',$this->from.' 00:00:00');
            $index = false;
        }
        if($this->to != null){
            if($index)$where->orWhere('created_at','<=',$this->to.' 23:59:59');
            else $where->Where('created_at','<=',$this->to.' 23:59:59');
            $index = false;
        }
        if($this->type != null){
            if($index)$where->orWhere('type','=',$this->type);
            else $where->Where('type','=',$this->type);
        }
        if($this->customer_id != null){
            $where->Where('customer_id','=',$this->customer_id);
        }
        if($this->subscription_id != null){
            $subscription = Subscription::find($this->subscription_id);
            $where->where('plan_id','=',$subscription->plan_id);
            $where->where('customer_id',$subscription->customer_id);
        }
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('id', 'ASC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $transaction){
            $items[$index]['customer_name'] = $transaction->customer->first_name.' '.$transaction->customer->last_name;
            if($transaction->status=="Completed" && $transaction->invoice)$items[$index]['invoice_id'] = $transaction->invoice->id;
        }        
        return $response;
    }
    public static function generate($paymentSubscription,$plan,$subscription){
        $transaction = new self;
        $transaction->customer_id = $paymentSubscription->customer_id;
        switch($paymentSubscription->provider){
            case 'nmi':
                $transaction->type = 0;
            break;
            case 'paypal':
                $transaction->type = 1;
            break;
        }
        $transaction->plan_id = $plan->id;
        $transaction->content = 'nmi';
        $transaction->coupon_id = $subscription->coupon_id;
        $transaction->payment_subscription_id = $paymentSubscription->subscription_id;
        $total = $paymentSubscription->nextPaymentAmount();
        $transaction->total = $total;
        $transaction->done_date = date('Y-m-d H:i:s');
        $transaction->frequency = $subscription->convertFrequencyString($paymentSubscription->getFrequency());
        $transaction->status = 'Pending';
        $transaction->save();
        return $transaction;
    }
    public static function renewalGenerate($paymentSubscription){
        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
        $transaction = new self;
        $transaction->customer_id = $customerId;
        switch($provider){
            case 'nmi':
                $transaction->type = 0;
            break;
            case 'paypal':
                $transaction->type = 1;
            break;
        }
        $referralCoupon = Coupon::findCouponWithReferral($couponId,$customerId);
        $transaction->plan_id = $planId;
        $transaction->content = 'nmi renewal';
        if($referralCoupon)$transaction->coupon_id = $referralCoupon->id;
        $transaction->payment_subscription_id = $paymentSubscription->subscription_id;
        $total = $paymentSubscription->nextPaymentAmount($referralCoupon);
        $transaction->total = $total;
        $transaction->done_date = date('Y-m-d H:i:s');
        $subscription = new Subscription;
        $transaction->frequency = $subscription->convertFrequencyString($frequency);
        $transaction->status = 'Pending';
        $transaction->save();
        return $transaction;
    }
    public function searchAll(){
        $where = Transaction::where(function($query){
            if($this->customer_name!=null){
                $query->whereHas('customer', function($q){
                    $q->where('first_name','like','%'.$this->customer_name.'%')->orWhere('last_name','like','%'.$this->customer_name.'%');
                });
            }
        });
        $index = true;
        if($this->from != null){
            $where->Where('created_at','>=',$this->from.' 00:00:00');
            $index = false;
        }
        if($this->to != null){
            if($index)$where->orWhere('created_at','<=',$this->to.' 23:59:59');
            else $where->Where('created_at','<=',$this->to.' 23:59:59');
            $index = false;
        }
        if($this->type != null){
            if($index)$where->orWhere('type','=',$this->type);
            else $where->Where('type','=',$this->type);
            $index = false;
        }
        return $where->get();
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            if($request->input($property)!="" && $request->input($property)!=null && ($property=='type' && $request->input($property)!="all" || $property!='type') ){
                $this->{$property} = $request->input($property);
            }else{
            }
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function createInvoice(){
        if($this->invoice==null && $this->total>0){
            $invoice = new Invoice;
            $invoice->transaction_id = $this->id;
            $invoice->save();
        }
    }
}
