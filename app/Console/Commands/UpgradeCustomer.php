<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customer;
use App\Transaction;
use App\PaymentSubscription;

class UpgradeCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upgrade:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $customers = Customer::whereHas('transactions')->get();
        foreach($customers as $customer){
            $transactions = Transaction::whereCustomerId($customer->id)->whereStatus('Completed')->where('total','>',0)->get();
            // if($customer->first_payment_date)print_r($customer->first_payment_date);
            // else print_r('no payment date');
            $doneDate = null;
            if(count($transactions)>0){
                foreach($transactions as $transaction){
                    $doneDate = date(date('Y-m-d',strtotime($transaction->done_date)));
                    break;
                }
            }
            // if($doneDate ){
            //     if(isset($customer->subscriptions[0])){
            //         if($customer->subscriptions[0]->status == 'Cancelled' && $customer->subscriptions[0]->cancelled_date<$doneDate){
            //             $paymentSubscriptions = PaymentSubscription::whereCustomerId($customer->id)->get();
            //             if(count($paymentSubscriptions)>0)
            //             foreach($paymentSubscriptions as $paymentSubscription){
            //                 if($paymentSubscription->status != 'Cancelled'){
            //                     print_r($customer->id);
            //                     print_r("\n");
            //                 }
            //             }
            
            //         }
            //     }
            // }
            if($customer->first_payment_date == $doneDate){
                // print_r($doneDate);
                // print_r('same');
            }else{
                // var_dump($doneDate);print_r('not same');
                $customer->first_payment_date = $doneDate;
                $customer->save();
            }
        }
    }
}
