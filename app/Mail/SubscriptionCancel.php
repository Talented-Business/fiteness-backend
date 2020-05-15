<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCancel extends Mailable
{
    use Queueable, SerializesModels;
    public $first_name;
    public $frequency;
    public $cancel_date;
    public $subscription_date;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name,$frequency,$cancel_date,$subscription_date)
    {
        $this->first_name = $first_name;
        $this->frequency = $frequency;
        $this->cancel_date = $cancel_date;
        $this->subscription_date = $subscription_date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->first_name.", la cancelación del plan Fitness ".$this->frequency." se ha realizado con éxito";
        return $this->subject($subject)->view('emails.subscriptions.cancel');
    }
}