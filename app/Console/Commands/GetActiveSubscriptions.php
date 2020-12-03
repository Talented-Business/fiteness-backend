<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Subscription;
use App\Transaction;
use App\PaymentSubscription;
use App\Invoice;
class GetActiveSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:active';

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
        if(true){
            $this->getAllActiveCustomers();
        }
        if(false){
            $this->getActiveCustomers();
        }
        if(false){
            $this->getFreeActiveCustomers();
        }
    }
    private function getAllActiveCustomers(){
        $subscriptions = Subscription::whereStatus('Active')->get();
        $activeCustomers = [
            3,15,28,46,60,77,108,111,132,166,176,1515,1532,1563,1585,1599,1538,1629,1624,1708,1711,1710,1716,1730,1746,1755,1741,1760,1807,61,1874,1671,
            1965,1803,1679,1601,2062,1999,1883,2266,1667,1938,2287,2356,2373,2394,2408,1958,2261,2561,1577,2802,2712,2945,1628,1523,2963,3006,
            1670,3116,3138,1718,3294,3307,3328,3331,1754,3395,3527,3538,3518,3559,3578,3585,3590,3604,3612,3624,3626,3663,3680,806,3724,3769,
            3820,3851,3856,3857,3863,1560,3935,3959,3961,3963,3965,3966,3967,3968,3969,3970,3971,3964,3979,2352,3608,4064,4083,4041,4047,4172,
            4253,4285,4290,4311,4327,4328,4423,4410,4409,4450,4475,4508,4411,4585,4644,4686,4695,4685,4698,4718,4741,4819,4827,4828,4851,4859,
            4920,4931,4937,4941,4942,4964,4991,4584,5018,5021,5040,5050,5054,5076,5084,5099,5100,5111,5132,5180,5032,5189,5206,3621,4817,5282,
            5176,3960,5375,5385,2917,4415,5405,5411,5419,5464,5473,2183,5512,5528,5187,5559,5564,5567,5569,5576,5578,5588,5593,5610,5618,5622,
            5556,5634,5640,5650,3619,5662,5673,5669,5222,5624,5689,5690,5699,5700,2280,5710,5714,5715,5717,5719,3713,5723,5730,5731,5738,5739,5585];
        // print_r(count($activeCustomers));
        $diffCustomers = [];
        $newCustomers = [];
        foreach($subscriptions as $subscription){
            $diffCustomers[] = $subscription->customer_id;
            if(in_array($subscription->customer_id,$activeCustomers)){
                
            }else{
                $newCustomers[] = $subscription->customer_id;
            }
            // print_r($subscription->customer_id);
            // print_r(",");
        }
        print_r(array_diff($diffCustomers,$activeCustomers));
    }
    private function getFreeActiveCustomers(){
        $subscriptions = Subscription::where('plan_id',1)->whereStatus('Active')->get();
        foreach($subscriptions as $subscription){
            print_r($subscription->customer_id);
            print_r(",");
            // print_r("\n");
        }
    }
    private function getActiveCustomers(){
        $freeActiveCustomerIds = [5018,4415,5187,5559,      5564,        5567,        5569,        5578,        5588,        5593,        5610,        5618,
        5622,        5556,        5634,        5640,        5650,        3619,        5662,        5673,        5669,        5222,        5624,        5689,
        5690,        5699,        5700,        2280,        5710,        5714,        5715,        5717,        5719,        3713,        5723,        5730,
        5731,        5738,        5739,        5585];
        $from =4654;
        $to = 4941;
        $transactions = Transaction::where('id','>=',$from)->where('id','<=',$to)->get();
        $customerIds = [];
        foreach($transactions as $transaction){
        // $customer = $transaction->customer;
            if(in_array($transaction->customer_id,$customerIds)===false)$customerIds[] = $transaction->customer_id;
            Invoice::whereTransactionId($transaction->id)->delete();
        }
        foreach($customerIds as $customerId){
            $subscription = Subscription::whereCustomerId($customerId)->first();
            if($subscription){
                $transaction = Transaction::whereCustomerId($customerId)->whereStatus('Completed')->where('id','<',$from)->orderBy('id','desc')->first();
                if($transaction == null){
                    // print_r($customerId);
                    // print_r("\n");
                    $subscription->transaction_id = null;
                    if($subscription->status == 'Cancelled')$subscription->status = 'Active';
                    $subscription->end_date = null;
                    $subscription->save();
                    $paymentSubscription = PaymentSubscription::whereCustomerId($customerId)->orderBy('created_at','desc')->first();
                    if($paymentSubscription->status == 'Cancelled'){
                        $paymentSubscription->status = 'Approved';
                        $paymentSubscription->transaction = 'Changed';
                        $paymentSubscription->save();
                    }
                }else{
                    if($transaction->id>$from){
                        // print_r($transaction->id);
                        // print_r("\n");
                    }else{
                        $subscription->transaction_id = $transaction->id;
                        if($subscription->status == 'Cancelled')$subscription->status = 'Active';
                        $subscription->end_date = null;
                        $subscription->save();
                        $paymentSubscription = PaymentSubscription::whereCustomerId($customerId)->orderBy('created_at','desc')->first();
                        if($paymentSubscription->status == 'Cancelled'){
                            $paymentSubscription->status = 'Active';
                            $paymentSubscription->save();
                        }
                    }
                }
                Transaction::whereCustomerId($customerId)->where('id','>=',$from)->where('id','<=',$to)->delete();
            }
        }
    }
    private function getActiveSubscription(){
        $subscriptions = Subscription::whereStatus("Active")->get();
        // print_r("[");
        foreach($subscriptions as $subscription){
            // print_r("[");
            // print_r($subscription->id);
            // print_r(",");
            // print_r($subscription->transaction_id);
            // print_r(",");
            // print_r("'");
            // print_r($subscription->end_date);
            // print_r("'");
            // print_r("]");
            // print_r(",");
            // print_r("\n");
        }
        // print_r("]");
    }

}
