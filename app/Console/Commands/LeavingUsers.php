<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customer;
use App\Transaction;

class LeavingUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaving:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get leaving users';

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
            $this->getCustomers();
        }
    }
    private function getCustomers(){
        $customers = Customer::all();
        $months = [];
        foreach($customers as $customer){
            $m = date('m',strtotime($customer->created_at));
            if(isset($months[$m])){
                $months[$m]++;
            }else{
                $months[$m] = 1;
            }
        }
        print_r($months);
    }
}
