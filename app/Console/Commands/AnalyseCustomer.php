<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customer;
use App\Subscription;

class AnalyseCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze:customers';

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
        $this->updateUsername();
        // $this->getCustomersLeavingOneAgo();
    }
    private function updateUsername(){
        $customers = Customer::all();
        foreach($customers as $customer){
            $customer->username = "user".$customer->id;
            $customer->save();
        }
    }
    private function getCurrentActiveCustomers(){
        $subscriptions = Subscription::whereStatus('Active')->whereNull('end_date')->get();
        foreach($subscriptions as $subscription){
            print_r($subscription->customer_id);print_r(',');
        }
    }
    private function getCustomersLeavingOneAgo(){
        // $customerIds = [3,15,28,46,60,77,92,108,111,132,166,176,1515,1532,1563,1585,1599,1538,1629,1609,1624,1708,1711,1710,1716,1730,1746,1755,1751,1741,1760,1807,61,1942,1874,1671,1965,1998,1803,1679,1601,2062,1999,1883,2266,1667,1938,2287,2356,2373,2394,2408,1958,2261,2561,1577,2712,2945,1628,1523,2963,3006,1670,3089,3116,3138,3196,3200,1718,3294,3307,3328,3330,3331,1754,3354,3395,3511,3527,3518,3559,3578,3585,3590,3604,3612,3624,3626,3654,3663,3680,806,3724,3769,3819,3820,3851,3856,3857,3863,3864,1560,3935,3959,3961,3963,3965,3966,3967,3968,3969,3970,3971,3964,3979,2352,3608,4064,4083,4104,4041,4047,4172,4253,4285,4290,4311,4327,4328,4423,4410,4409,4475,4508,4585,4412,4644,2685,4686,4690,4693,4695,4685,4698,4718,4741,4144,4819,4827,4828,4849,4851,4859,4582,4898,4920,4931,4937,4743,4941,4942,4964,4991,4584,5021,5040,5050,5054,5076,5084,5099,5100,5111,5132,5173,5180,5032,5189,5203,3637,3621,4817,5253,5282,5356,5176,3960,5375,5385,2917,5399,4415,5405,5411,5419,5403,5446,5464,5472,5473,5488,5500,2183,5512,5528,5187,5559,5564,5567,5569];
        $customerIds = [3,15,28,46,60,77,92,108,111,132,166,176,1515,1532,1563,1585,1599,1538,1629,1609,1624,1708,1711,1710,1716,1730,1746,1755,1751,1741,1760,61,1874,1671,1965,1803,1679,1601,2062,1999,1883,2266,1667,1938,2287,2356,2373,2394,2408,1958,2261,2561,1577,2712,2945,1628,1523,2963,3006,1670,3089,3116,3138,3196,3200,1718,3307,3328,3330,3331,1754,3395,3511,3527,3559,3578,3585,3590,3604,3612,3624,3626,3663,3724,3820,3851,3864,1560,3935,3959,3961,3963,3965,3966,3967,3968,3969,3970,3971,3964,3979,2352,3608,4064,4083,4041,4047,4172,4253,4285,4290,4311,4327,4328,4423,4410,4409,4475,4508,4585,4644,4686,4693,4695,4685,4698,4718,4741,4819,4827,4828,4849,4859,4582,4898,4920,4931,4937,4941,4942,4964,4584,5021,5040,5050,5054,5084,5099,5100,5111,5132,5173,5180,5032,5189,5203,3637,3621,4817,5253,5282,5356,5176,3960,5375,5385,2917,5399,4415,5405,5411,5419,5403,5446,5464,5472,5473,5488,5500,2183,5512,5528,5187,5559,5564,5567,5569];
        $subscriptions = Subscription::whereIn('customer_id',$customerIds)->get();
        foreach($subscriptions as $subscription){
            if($subscription->status == 'Active' && $subscription->end_date){
                print_r($subscription->customer_id);print_r(',');
                // print_r("\n");
            }
        }
    }
    private function getFreeSubscriptionCustomers(){
        $subscriptions = Subscription::wherePlanId(1)->get();
        foreach($subscriptions as $subscription){
            if($subscription->status == 'Cancelled'){
                print_r($subscription->customer_id);
                if($subscription->cancelled_date<'2020-10-17 00:00:00'){
                    print_r('before');
                }else{
                    print_r('after');
                }
                print_r("\n");
            }
        }
    }
}
