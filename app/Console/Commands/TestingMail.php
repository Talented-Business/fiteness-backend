<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\MailQueue;
use Mail;


class TestingMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mail';

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
        $data = ['first_name'=>"testing first",'last_name'=>"testing last",'email'=>"testing@gmail.com",'gender'=>"Female",'view_file'=>'emails.customers.create','subject'=>'Checkout Completed'];
        Mail::to("sui201837@gmail.com", config('mail.from.name'))->queue(new MailQueue($data));
    }
}
