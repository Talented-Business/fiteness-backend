<?php

namespace App\Payment;

use Illuminate\Support\Facades\Cache;
use App\Mail\RenewalPaymentBankReminder;
use Mail;


class Bank
{
    public static function isOld($subscription){
        if($subscription && $subscription->customer){
            $transactions = $subscription->customer->transactions()->paid();
            return $transactions->count()>1;
        }
        return false;
    }
    public static function getEndDate($subscription){
        return $subscription->getExpirationTime();
    }
    public static function scraping($subscription){
        $endDate = self::getEndDate($subscription);
        if(strtotime($endDate) < time() ){
            if($subscription->status =="Active"){
                $subscription->end_date = $endDate;
                $subscription->status = 'Expired';
                $subscription->save();
            }
        }
        $timeDiff = round((time() - strtotime($endDate))/3600);
        $reminder = Cache::get('reminder'.$subscription->id);
        if($timeDiff >= -14 * 24 && $timeDiff <= -14 * 24 + 1 && $reminder == null){
            //notification;
            \App\Models\Notification::bankExpiration($subscription->customer_id, 14);
            Cache::put('reminder'.$subscription->id, $subscription->customer_id, 3600);
        }
        if($timeDiff >= -7 * 24 && $subscription->reminder_before_seven!=1){
            //send mail reminder
            Mail::to($subscription->customer->email)->send(new RenewalPaymentBankReminder($subscription->customer->first_name,$endDate,'before_seven'));
            $subscription->reminder_before_seven = 1;
            $subscription->save();
            //notification;
            \App\Models\Notification::bankExpiration($subscription->customer_id, 7);
        }
        $reminderTree = Cache::get('reminder-tree-'.$subscription->id);
        if($timeDiff >= -3 * 24 && $timeDiff <= -3 * 24 + 1 && $reminderTree == null){
            //notification;
            \App\Models\Notification::bankExpiration($subscription->customer_id, 3);
            Cache::put('reminder-tree-'.$subscription->id, $subscription->customer_id, 3600);
        }
        if($timeDiff >= -24 && $subscription->reminder_before_one!=1){
            //send mail reminder
            Mail::to($subscription->customer->email)->send(new RenewalPaymentBankReminder($subscription->customer->first_name,$endDate,'before_one'));
            $subscription->reminder_before_one = 1;
            $subscription->save();
            //notification;
            \App\Models\Notification::bankExpiration($subscription->customer_id, 1);
        }
        if($timeDiff >= 24 && $subscription->reminder_after_one!=1){
            //send mail reminder
            Mail::to($subscription->customer->email)->send(new RenewalPaymentBankReminder($subscription->customer->first_name,$endDate,'after_one'));
            $subscription->reminder_after_one = 1;
            $subscription->save();
        }
    }
}
