<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CustomerShortcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:shortcode';

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
        $this->customerShortcode();
        // $this->shortcode();
    }
    private function customerShortcode(){
        $customerShortcode = \App\CustomerShortcode::find(1);
        $change = $customerShortcode->getChange();
        var_dump($change);
    }
    private function shortcode(){
        $customerIds = [3,15,28, 46, 77, 108];
        $shortCodes = \App\Shortcode::whereIn('id',[20])->get();
        foreach($shortCodes as $shortCode){
            // if($shortCode->level != 5) continue;
            echo "shortCode level:";
            var_dump($shortCode->level);
            if($shortCode->alternate_a){
                $alternateA = \App\Shortcode::find($shortCode->alternate_a);
                if($alternateA){
                    echo "alternateA level:";
                    var_dump($alternateA->level);        
                }
            }
            if($shortCode->alternate_b){
                $alternateB = \App\Shortcode::find($shortCode->alternate_b);
                if($alternateB){
                    echo "alternateB level:";
                    var_dump($alternateB->level);        
                }
            }
            $customer = \App\Customer::find($customerIds[0]);
            echo "current condition:";
            var_dump($customer->current_condition);
            $result = \App\CustomerShortcode::createFirst($customer, $shortCode);
            var_dump($result);
        }
        // $customer = \App\Customer::find($customerIds[1]);
        // $shortcode = \App\Shortcode::find(81);
        // $customerShortcode = \App\CustomerShortcode::createFirstItem($customer, $shortcode);
    }
}
