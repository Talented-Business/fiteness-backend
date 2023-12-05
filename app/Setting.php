<?php

namespace App;

class Setting
{
    const CART_ABANDONED_TIME = 'cart_abandoned_time';
    const CART_ABANDONED_TIME_UNIT = 'cart_abandoned_time_unit';
    const CART_NEW_CUSTOMER_COUPON_ID = 'cart_new_customer_coupon_id';
    const CART_RENEWAL_CUSTOMER_COUPON_ID = 'cart_renewal_customer_coupon_id';
    const REFERRAL_DISCOUNT = 'referral_discount';
    const TAG_LINE = "tag_line";
    const IMAGE_SIZES = [            
        'x-small' => '50X50', 
        'small' => '150X150', 
        'medium' => '500X500', 
        'large' => '650X500', 
        'x-large' => '1024X1024', 
    ];
    public static function convertSizes(){
        $sizes = array_values(Setting::IMAGE_SIZES);
        $y = [];
        foreach($sizes as $size){
            $s = explode('X',$size);
            $y[] = $s;
        }
        return $y;
    }
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
        return ['time'=>$cartAbandonedTime,'unit'=>$cartAbandonedTimeUnit,'new_coupon_id'=>$newCustomerCouponId,'renewal_coupon_id'=>$renewalCustomerCouponId];
    }
    public static function saveCart($time,$unit,$newCustomerCouponId,$renewalCustomerCouponId){
        $config = new Config;
        $config->updateConfig(self::CART_ABANDONED_TIME, $time);
        $config->updateConfig(self::CART_ABANDONED_TIME_UNIT, $unit);
        $config->updateConfig(self::CART_NEW_CUSTOMER_COUPON_ID, $newCustomerCouponId);
        $config->updateConfig(self::CART_RENEWAL_CUSTOMER_COUPON_ID, $renewalCustomerCouponId);
    }
    public static function getReferralDiscount(){
        $config = new Config;
        $referralDiscount = $config->findByName(self::REFERRAL_DISCOUNT);
        if($referralDiscount==null)$referralDiscount=20;
        return $referralDiscount;
    }
    public static function saveReferralDiscount($referralDiscount){
        $config = new Config;
        $config->updateConfig(self::REFERRAL_DISCOUNT, $referralDiscount);
    }
    public static function getTagLine(){
        $config = new Config;
        $tagLine = $config->findByName(self::TAG_LINE);
        if($tagLine==null)$tagLine="";
        return $tagLine;
    }
    public static function saveTagLine($tagLine){
        $config = new Config;
        $config->updateConfig(self::TAG_LINE, $tagLine);
    }
}
