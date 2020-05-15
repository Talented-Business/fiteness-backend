<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Customer;

class NotifyNonSubscriber extends Mailable
{
    use Queueable, SerializesModels;
    public $customer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "¡Hola ".$this->customer->first_name."! Estoy a la orden para cualquier consulta que tengas sobre Fitemos :)";
        return $this->subject($subject)->from($address = 'hola@fitemos.com', $name = 'Marifer de Fitemos')->view('emails.subscriber.non');
    }
}
