<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CartAbandonedNew extends Mailable
{
    use Queueable, SerializesModels;
    public $firstName;
    public $percent;
    public $url;
    public $couponCode;
    public $couponName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstName,$percent,$url,$couponCode,$couponName)
    {
        $this->firstName = $firstName;
        $this->percent = $percent;
        $this->url = $url;
        $this->couponCode = $couponCode;
        $this->couponName = $couponName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->firstName.", ¡Cambia tu vida para siempre! Con esta oferta única";
        return $this->subject($subject)->from($address = 'hola@fitemos.com', $name = 'Marifer de Fitemos')->view('emails.cart.new');
    }
}
