<?php

namespace App\Payment;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\SubscriptionPlan;

class Bank
{
    public static function isOld($subscription){
        if($subscription && $subscription->customer){
            $transactions = $subscription->customer->transactions()->paid();
            return $transactions->count()>0;
        }
        return false;
    }
    public static function getEndDate($subscription){
        if(self::isOld($subscription)){
            $currentStartDate = $subscription->transaction->done_date;
        }else{
            $service = $subscription->plan->service;
            $freePlan = SubscriptionPlan::whereServiceId($service->id)->whereType('Free')->first();
            $currentStartDate = date('Y-m-d H:i:s',strtotime($subscription->transaction->done_date)+$freePlan->free_duration*3600*24);//
        }
        $months = $subscription->convertMonths();
        return date('Y-m-d H:i:s', strtotime("+$months months", strtotime($currentStartDate)));
    }
    public static function scrape($subscription){
        $endDate = self::getEndDate($subscription);
        if(strtotime($endDate) < time() ){
            $subscription->endDate = $endDate;
            $subscription->status = 'Expired';
            $subscription->save();
        }
    }
}
