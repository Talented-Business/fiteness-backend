<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class updateSocialAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:social';

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
        $users = User::all();
        foreach($users as $user){
            if($user->provider == "google"){
                $user->google_provider_id = $user->provider_id;
                $user->google_name = $user->name;
            }
            if($user->provider == "facebook"){
                $user->facebook_provider_id = $user->provider_id;
                $user->facebook_name = $user->name;
            }
            $user->save();
        }
    }
}
