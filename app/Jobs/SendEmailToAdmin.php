<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;
use Mail;

class SendEmailToAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $mail;
    private $mailAddress;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailAddress,Mailable $mail)
    {
        $this->mail = $mail;
        $this->mailAddress = $mailAddress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->mailAddress)Mail::to($this->mailAddress)->send($this->mail);
    }
}
