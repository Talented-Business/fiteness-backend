<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Config;

class StartQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:queue';

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
        exec("ps -eo cmd", $output, $return);
        $queue = 0;
        $config = new Config;
        if(is_array($output)){
            foreach($output as $value){
                if(strpos($value,'artisan queue:work')){
                    $config->updateConfig('subscription_queue'.$queue, $value);
                    $queue++;
                }
            }
        }
        if($queue==0){
            exec("pgrep supervisord", $output1, $return1);
            if ($return1 == 0) {
                //echo "Ok, process is running\n";
                //var_dump($output1);
                if(isset($output1[0])){
                    $pid = $output1[0];
                    $command = "kill -s 15 ".$pid;
                    exec($command, $output, $return);
                    $config->updateConfig('kill_process', $command);
                }
            }else{
                exec("/home/fitemosc/.local/bin/supervisord -c /home/fitemosc/.local/bin/laravel-dev.conf", $output2, $return2);         
                $config->updateConfig('kill_process', 'command');
            }
        }
    }
}
