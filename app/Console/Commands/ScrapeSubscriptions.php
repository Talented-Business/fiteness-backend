<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Config;
use App\Subscription;
use App\PaymentSubscription;
use App\MauticClient;


class ScrapeSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscriptions scraping';

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
        $subscriptionScraping = $config->findByName('subscription_scraping');
        if ($subscriptionScraping === null) {
            $config->updateConfig('subscription_scraping', 'end');
            $subscriptionScraping = 'end';
        }
        if ($subscriptionScraping === 'end' || true) {
            $config->updateConfig('subscription_scraping', 'start');
            try{
                print_r(date("Y-m-d H:i:s"));
                print_r("\n");
                //start;
                if(env('INTERVAL_UNIT') == "MONTH") Subscription::scrape();
                //MauticClient::scrape();
            }
            catch(\Exception $e){
                echo 'error';
                echo $e->getMessage();
            }
            finally{
                $config->updateConfig('subscription_scraping', 'end');
            }
        } else {
            $count = $config->findByName('subscription_scraping_count');
            if($count === null){
                $count = 0;
            }
            $count = $count + 1;
            if($count>100){
                $config->updateConfig('subscription_scraping_count', 0);
                $config->updateConfig('subscription_scraping', 'end');
            }else $config->updateConfig('subscription_scraping_count', $count);
        }
        /*$s = new PaymentSubscription;
        $s->findSubscriptions();*/
    }
}
