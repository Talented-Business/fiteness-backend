<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Session;

class SessionTimeout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:timeout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count for sessions';

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
        $sessions = Session::whereInside('yes')->get();
        foreach($sessions as $session){
            if($session->inside == 'yes'){
                $diff = time() - strtotime($session->updated_at->toString());
                if($diff>env('SESSION_TIMEOUT')*60){
                    $session->inside = 'no';
                    $session->save();
                }
            }
            $session->customer->increaseRecord('session_count');
        }
    }
}
