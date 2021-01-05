<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Customer;
use App\Config;
use Illuminate\Mail\Mailable;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $customer;
    private $mail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, Mailable $mail)
    {
        $this->customer = $customer;
        $this->mail = $mail;
        $config = new Config;
        $construct = $config->findByName('sendmail construct'.$customer->id);
        $config->updateConfig('sendmail construct'.$customer->id, date("Y-m-d H:i:s"));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $config = new Config;
        $construct = $config->findByName('sendmail handle'.$this->customer->id);
        $config->updateConfig('sendmail handle'.$this->customer->id, date("Y-m-d H:i:s"));
        Mail::to($this->customer->email)->send($this->mail);
    }
}
