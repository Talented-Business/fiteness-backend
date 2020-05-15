<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Cart;
use App\Config;

class CartAbandon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:scraping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scraping cart abandoned';

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
        $subscriptionScraping = $config->findByName('cart_scraping');
        if ($subscriptionScraping === null) {
            $config->updateConfig('cart_scraping', 'end');
            $subscriptionScraping = 'end';
        }
        if ($subscriptionScraping === 'end' ) {
            $config->updateConfig('cart_scraping', 'start');
            try{
                //start;
                Cart::scrape();
            }
            catch(\Exception $e){
                echo 'error';
                echo $e->getMessage();
            }
            finally{
                $config->updateConfig('cart_scraping', 'end');
            }
        } else {
            $count = $config->findByName('cart_scraping_count');
            if($count === null){
                $count = 0;
            }
            $count = $count + 1;
            if($count>100){
                $config->updateConfig('cart_scraping_count', 0);
                $config->updateConfig('cart_scraping', 'end');
            }else $config->updateConfig('cart_scraping_count', $count);
        }
    }
}
