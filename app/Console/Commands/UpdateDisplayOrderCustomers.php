<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customer;
use App\Config;
use App\Done;

class UpdateDisplayOrderCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:updateDisplayOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command updates first_order_id, second_order_id on custmers table';

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
        $config = new Config;
        $oldColumn = $config->findByName('customer_display_order_column');
        if ($oldColumn === null || $oldColumn === 'second_order_id') {
            $column = 'first_order_id';
        }else {
            $column = 'second_order_id';
        }      
        $customers = Customer::all();
        // $i = 0;
        $lastMonthDay = date('y-m-d',strtotime( '-1 month', time() ));
        // $lastMonthDay = '2020-11-21';
        foreach($customers as $customer){
            $latestCompletedWorkoutCount = Done::whereCustomerId($customer->id)->where('done_date','>',$lastMonthDay)->count();
            $isPicture = $customer->user->avatar?true:false;
            $active = $customer->hasActiveSubscription();
            $orderWeight = $latestCompletedWorkoutCount*4;
            if ( $isPicture ) $orderWeight += 2;
            if ( !$active ) $orderWeight += 1;
            // if($latestCompletedWorkoutCount>20){
            //     print_r($customer->id);
            //     var_dump($isPicture);
            // }
            // $i++;
            $customer->{$column} = $orderWeight;
            $customer->save();
        }
        $config->updateConfig('customer_display_order_column', $column);  
    }
}
