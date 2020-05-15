<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewalPaymentNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $firstName;
    public $frequencyString;
    public $frequency;
    public $coupon;
    public $amount;
    public $total;
    public $doneDate;
    public $nextPaymentDate;
    public $nextPaymentTotal;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstName,$frequencyString,$frequency,$amount,$total,$coupon,$doneDate,$nextPaymentDate,$nextPaymentTotal)
    {
        $this->firstName = $firstName;
        $this->frequencyString = $frequencyString;
        $this->frequency = $frequency;
        $this->amount = $amount;
        $this->total = $total;
        $this->doneDate = $doneDate;
        $this->nextPaymentDate = $nextPaymentDate;
        $this->nextPaymentTotal = $nextPaymentTotal;
        if($coupon){
          $this->coupon = $coupon;
          $this->coupon['amount'] = round($amount - $total,2);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("¡Listo! Tu comprobante de renovación")->view('emails.payments.renewal');
    }
}
