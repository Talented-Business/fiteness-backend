<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CartAbandonedRenewal extends Mailable
{
    use Queueable, SerializesModels;
    public $firstName;
    public $percent;
    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstName,$percent,$url)
    {
        $this->firstName = $firstName;
        $this->percent = $percent;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "¡Te extrañamos! Tenemos algo para tí.";
        return $this->subject($subject)->from($address = 'hola@fitemos.com', $name = 'Marifer de Fitemos')->view('emails.cart.renewal');
    }
}
