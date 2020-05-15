<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CouponTrialBefore extends Mailable
{
    use Queueable, SerializesModels;
    public $first_name;
    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name,$url)
    {
        $this->first_name = $first_name;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->first_name.", mañana es tu último día y tenemos un ¡GRAN OBSEQUIO! para ti.";
        return $this->subject($subject)->view('emails.coupons.trial_before');
    }
}
