<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Setting
{
    const CART_ABANDONED_TIME = 'cart_abandoned_time';
    const CART_ABANDONED_TIME_UNIT = 'cart_abandoned_time_unit';
    const CART_NEW_CUSTOMER_COUPON_ID = 'cart_new_customer_coupon_id';
    const CART_RENEWAL_CUSTOMER_COUPON_ID = 'cart_renewal_customer_coupon_id';
    const CART_NEW_CUSTOMER_SECOND_ABANDONED_TIME = 'cart_new_customer_second_abandoned_time';
    public static function getCart(){
        $config = new Config;
        $cartAbandonedTime = $config->findByName(self::CART_ABANDONED_TIME);
        if($cartAbandonedTime==null)$cartAbandonedTime=3;
        $cartAbandonedTimeUnit = $config->findByName(self::CART_ABANDONED_TIME_UNIT);
        if($cartAbandonedTimeUnit==null)$cartAbandonedTimeUnit='h';
        $newCustomerCouponId = $config->findByName(self::CART_NEW_CUSTOMER_COUPON_ID);
        if($newCustomerCouponId == null) $newCustomerCouponId = '';
        $renewalCustomerCouponId = $config->findByName(self::CART_RENEWAL_CUSTOMER_COUPON_ID);
        if($renewalCustomerCouponId == null) $renewalCustomerCouponId = '';
        $newSecondMail = $config->findByName(self::CART_NEW_CUSTOMER_SECOND_ABANDONED_TIME);
        if($newSecondMail == null) $newSecondMail = '';
        return ['time'=>$cartAbandonedTime,'unit'=>$cartAbandonedTimeUnit,'new_coupon_id'=>$newCustomerCouponId,'renewal_coupon_id'=>$renewalCustomerCouponId,'new_second_mail'=>$newSecondMail];
    }
    public static function saveCart($time,$unit,$newCustomerCouponId,$renewalCustomerCouponId,$newSecondMail){
        $config = new Config;
        $config->updateConfig(self::CART_ABANDONED_TIME, $time);
        $config->updateConfig(self::CART_ABANDONED_TIME_UNIT, $unit);
        $config->updateConfig(self::CART_NEW_CUSTOMER_COUPON_ID, $newCustomerCouponId);
        $config->updateConfig(self::CART_RENEWAL_CUSTOMER_COUPON_ID, $renewalCustomerCouponId);
        $config->updateConfig(self::CART_NEW_CUSTOMER_SECOND_ABANDONED_TIME, $newSecondMail);
    }
}
