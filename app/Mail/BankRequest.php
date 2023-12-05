<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Customer;

class BankRequest extends Mailable
{
    use Queueable, SerializesModels;
    public $customer;
    public $duration;
    public $amount;
    public $bankFee;
    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer,$duration,$amount,$bankFee)
    {
        $this->customer = $customer;
        $this->duration = $duration;
        $this->amount = $amount;
        $this->bankFee = $bankFee;
        $this->url = config('app.url');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "¡Hola ".$this->customer->first_name."! Solicitud de transferencia bancaria";
        return $this->subject($subject)->from($address = 'hola@fitemos.com', $name = 'Marifer de Fitemos')->view('emails.payments.bank');
    }
}
