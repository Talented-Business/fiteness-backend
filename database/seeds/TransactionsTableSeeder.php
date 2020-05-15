<?php

use Illuminate\Database\Seeder;
use App\Transaction;
use App\SubscriptionPlan;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $frequencies = ['Mensual', 'Trimestral', 'Semestral', 'Anual','Once'];
        $plan  = SubscriptionPlan::find(2);
        $amounts = [$plan->month_1,$plan->month_3,$plan->month_6,$plan->month_12];
        $months = [1,3,6,12];
        for($i = 0;$i<100;$i++){
            $transaction = new Transaction;
            $transaction->type=rand(0,1);
            $transaction->content = 'Transaction credit card type '.$i;
            $transaction->customer_id=$i+1;//rand(1,100);
            $transaction->plan_id=2;
            $frequency = rand(0,4);
            if($frequency == 4)continue;
            $transaction->total=$amounts[$frequency];
            $transaction->frequency = $frequencies[$frequency];
            $transaction->start_date="2019-12-01";
            $added_timestamp = strtotime('+'.$months[$frequency].' month', strtotime($transaction->start_date));
            $transaction->end_date=date('Y-m-d', $added_timestamp); 
            $states = ['Pending','Completed','Declined'];
            $transaction->status=$states[rand(0,2)];
            $j = rand(10,30);
            $transaction->created_at = "2019-12-$j 00:00:00";
            $transaction->save();
        }

    }
}
