<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Config;
use App\MauticClient;

class ScrapeMautic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mautic:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mautic:scrape';

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
        $mauticScraping = $config->findByName('mautic_scraping');
        if ($mauticScraping === null) {
            $config->updateConfig('mautic_scraping', 'end');
            $mauticScraping = 'end';
        }
        if ($mauticScraping === 'end') {
            $config->updateConfig('mautic_scraping', 'start');
            try{
                //start;
		        MauticClient::scrape();
            }
            catch(\Exception $e){
                echo 'error';
                echo $e->getMessage();
            }
            finally{
                $config->updateConfig('mautic_scraping', 'end');
            }
        } else {
            $count = $config->findByName('mautic_scraping_count');
            if($count === null){
                $count = 0;
            }
            $count = $count + 1;
            if($count>15){
                $config->updateConfig('mautic_scraping_count', 0);
                $config->updateConfig('mautic_scraping', 'end');
            }else $config->updateConfig('mautic_scraping_count', $count);
        }
    }
}
