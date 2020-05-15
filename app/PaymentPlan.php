<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class PaymentPlan extends Model
{
    protected $table = 'payment_plans';
    protected $fillable = ['slug','plan_id'];
    private $pageSize;
    private $pageNumber;
    private static $searchableColumns = ['search'];
    public static function validateRules(){
        return array(
            'slug'=>'required',
            'plan_id'=>'required',
        );
    }
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
    }
    public function analyzeSlug(){
        $values = explode('-',$this->slug);
        $provider = $values[0];
        $planId = $values[1];
        $customerId = $values[2];
        $frequency = $values[3];
        if(isset($values[4])){
            $couponId = $values[4];
        }else{
            $couponId = null;
        }
        return [$provider,$planId,$customerId,$frequency,$couponId,$this->slug];
    }
    public static function createSlug($servicePlan, $customerId, $coupon, $frequency,$gateway='paypal'){
        $slug = $gateway.'-'.$servicePlan->id . '-' . $customerId . '-' . $frequency;
        if ($coupon && $coupon->status == 'Active') {
            $slug .= '-'.$coupon->id;
        }
        return $slug;
    }
    public static function createOrUpdate($servicePlan, $customerId, $coupon, $frequency,$gateway){
        if($coupon&&$coupon->validate($customerId) === false)$coupon = null;
        $slug = self::createSlug($servicePlan, $customerId, $coupon, $frequency,$gateway);
        $plan = PaymentPlan::whereSlug($slug)->first();
        if($plan == null){
            $plan = new PaymentPlan;
            $plan->slug = $slug;
            $plan->plan_id = $slug;
            $plan->save();
        }
        return $plan;
    }
    public function search(){
    }
    public function assignSearch($request){
    }
}
