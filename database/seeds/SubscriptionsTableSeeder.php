<?php

use Illuminate\Database\Seeder;
use App\Subscription;
use App\Customer;
use App\Transaction;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = Customer::all();
        $frequencies = ['Mensual', 'Trimestral', 'Semestral', 'Anual','Once'];
        $statuses = ['Pending','Active','On-Hold','Pending-Cancellation', 'Cancelled','Expired'];
        foreach($customers as $customer){
            $subscription = new Subscription;
            $transaction = Transaction::where('customer_id','=',$customer->id)->first();
            if($transaction){
                $subscription->plan_id=2;
                $subscription->transaction_id=$transaction->id;//
                $subscription->frequency=$transaction->frequency;//
                $subscription->start_date=$transaction->start_date;
                $subscription->end_date=$transaction->end_date;
                switch($transaction->status){
                    case "Completed":
                        $subscription->status='Active';
                    break;
                    case "Pending":
                        $subscription->status='Pending';
                    break;
                    case "Declined":
                        $subscription->status='On-Hold';
                    break;
                }
            }else{
                $subscription->plan_id=1;
                $subscription->frequency='Once';
                $i = rand(6,17);
                if($i<10)$i = '0'.$i;
                $subscription->start_date='2020-01-'.$i;
                $i += 7;
                $subscription->end_date='2020-01-'.$i;
                if($i<date("d"))$subscription->status='Expired';
                else $subscription->status='Active';
            }
            $subscription->customer_id=$customer->id;
            $subscription->save();
        }
    }
}
