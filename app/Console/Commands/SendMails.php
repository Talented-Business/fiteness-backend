<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Subscription;

class SendMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mails for suspended subscriptions with nmi';

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
        //
        Subscription::sendMails();
    }
}
