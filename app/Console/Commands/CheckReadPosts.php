<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckReadPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'After 48hours convert reading status into completed';

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
        $timezone = DB::select('select @@system_time_zone as timezone');
        // print_r($timezone[0]->timezone);
        $readings = DB::table('reading_posts')->where('status','pending')->get();
        $now = time();
        // echo date_default_timezone_get();print_r("\n");
        // echo date("Y-m-d H:i:s", $now-3600*48);print_r("\n");
        foreach($readings as $reading){
            if($now-3600*48>strtotime($reading->created_at)){
                DB::table('reading_posts')->where('id', $reading->id)->update(['status' => 'completed']);
            }
        }
    }
}
