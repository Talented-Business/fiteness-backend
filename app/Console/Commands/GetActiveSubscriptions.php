<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Subscription;
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
        $subscriptions = Subscription::whereStatus("Active")->get();
        print_r("[");
        foreach($subscriptions as $subscription){
            print_r("[");
            print_r($subscription->id);
            print_r(",");
            print_r($subscription->transaction_id);
            print_r(",");
            print_r("'");
            print_r($subscription->end_date);
            print_r("'");
            print_r("]");
            print_r(",");
            print_r("\n");
        }
        print_r("]");
    }
}
