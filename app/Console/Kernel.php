<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('coupons:report')
            ->weeklyOn(5, '0:00')
            ->evenInMaintenanceMode()
            ->timezone('America/Panama');
        $schedule->command('workouts:send')
            ->hourly()
            ->evenInMaintenanceMode()
            ->timezone('America/Panama');
        $schedule->command('subscriptions:mail')
            ->dailyAt('05:00')
            ->evenInMaintenanceMode()
            ->timezone('America/Panama');
        $schedule->command('subscriptions:scrape')
            ->everyTenMinutes()
            ->evenInMaintenanceMode()
            ->timezone('America/Panama');
        if(!$this->isRunQueue()){
            $schedule->command('queue:work --tries=3')
            ->everyTenMinutes()
            ->evenInMaintenanceMode()
            ->timezone('America/Panama');
        }    
        $schedule->command('mautic:scrape')
            ->hourly()
            ->evenInMaintenanceMode()
            ->timezone('America/Panama');
        $schedule->command('sessions:timeout')
            ->everyThirtyMinutes()
            ->evenInMaintenanceMode()
            ->timezone('America/Panama');
        $schedule->command('cart:scraping')
            ->everyThirtyMinutes()
            ->evenInMaintenanceMode()
            ->timezone('America/Panama');
        $schedule->command('db:backup')->hourly();
        $schedule->command('export:customers')
            ->dailyAt('20:00')
            ->evenInMaintenanceMode()
            ->timezone('America/Panama');
        $schedule->command('generate:report')
            ->dailyAt('23:59')
            ->evenInMaintenanceMode()
            ->timezone('America/Panama');
    }
    private function isRunQueue(){
        exec("ps -eo cmd", $output, $return);
        $queue = 0;
        if(is_array($output)){
            foreach($output as $value){
                if(strpos($value,'php artisan queue:work --tries=3')){
                    //$config->updateConfig('subscription_queue'.$queue, $value);
                    $queue++;
                }
            }
        }
        if($queue==0){
            return false;
        }
        return true;
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
