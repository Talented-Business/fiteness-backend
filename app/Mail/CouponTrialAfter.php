<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CouponTrialAfter extends Mailable
{
    use Queueable, SerializesModels;
    public $first_name;
    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $url)
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
        $subject = $this->first_name . ", ¡Aún tienes una oportunidad!.";
        return $this->subject($subject)->view('emails.coupons.trial_after');
    }
}
