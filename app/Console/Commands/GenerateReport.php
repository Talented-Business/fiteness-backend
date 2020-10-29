<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customer;
use App\Transaction;

class GenerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate customer report and save database';

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
        $customers = Customer::all();
        $registered = 0;
        $inactive = 0;
        $guest = 0;
        $exCustomer = 0;
        $exTrial = 0;
        $totalUsers = 0;
        $activeUsers = 0;
        $activeCustomers = 0;
        $activeTrials = 0;
        $leavingUsers = 0;
        $leavingCustomers = 0;
        $leavingTrials = 0;
        $totalCustomers = 0;
        $totalSales = 0; 
        $customerBase = 0; 
        $customerChurn = 0;
        $trialChurn = 0;
        foreach($customers as $customer){
            $registered++;
            $service = false;
            foreach($customer->subscriptions as $subscription){
                if($subscription->plan->service_id == 1){
                    $service = true;
                    $amount = Transaction::whereStatus('Completed')->whereCustomerId($customer->id)->sum('total');
                    if($amount>0){
                        $totalCustomers++;
                        $totalSales = $totalSales + $amount;
                    }
                    // print_r($amount);print_r("**");
                    if($subscription->status == "Active"){
                        $totalUsers++;
                        $status = "Active";
                        if($subscription->end_date){
                            $leavingUsers++;
                            if($amount>0){
                                $leavingCustomers++;
                                $customerChurn++;
                            }
                            else {
                                $leavingTrials++;
                                $trialChurn++;
                            }
                        }else{
                            $activeUsers++;
                            if($amount>0)$activeCustomers++;
                            else $activeTrials++;
                        }
                        $customerBase++;
                    }else{
                        if($subscription->status == "Cancelled"){
                            $totalUsers++;
                            $status = "Cancelled";
                            $inactive++;
                            if($amount>0){
                                $exCustomer++;
                                $customerChurn++;
                                $trialChurn++;
                            }
                            else {
                                $exTrial++;
                            }
                        }else{
                            $inactive++;
                            $guest++;              
                        }
                    }
                }
            }
            if(!$service){
                $guest++;
                $inactive++;
            }
        }
        $date = date('Y-m-d');
        \DB::table('reports')->updateOrInsert(['created_date'      =>$date],[
            'created_date'      =>$date,
            'registered'        =>$registered,
            'inactive'          =>$inactive,
            'guest'             =>$guest,
            'ex_customer'       =>$exCustomer,
            'ex_trial'          =>$exTrial,
            'total_users'       =>$totalUsers,
            'active_users'      =>$activeUsers,
            'active_customers'  =>$activeCustomers,
            'active_trials'     =>$activeTrials,
            'leaving_users'     =>$leavingUsers,
            'leaving_customers' =>$leavingCustomers,
            'leaving_trials'    =>$leavingTrials,
            'total_customers'   =>$totalCustomers,
            'total_sales'       =>$totalSales,
            'customer_base'     =>$customerBase,
            'customer_churn'    =>$customerChurn,
            'trial_churn'       =>$trialChurn,
        ]);
    }
}
