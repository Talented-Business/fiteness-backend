<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CouponReport extends Mailable
{
    use Queueable, SerializesModels;
    public $nonActive;
    public $active;
    public $total;
    public $couponName;
    public $fromDate;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($active,$nonActive,$total,$couponName,$fromDate)
    {
        $this->nonActive = $nonActive;
        $this->active = $active;
        $this->total = $total;
        $this->couponName = $couponName;
        $this->fromDate = $fromDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "From ".$this->fromDate." Report for coupon ".$this->couponName;
        return $this->subject($subject)->view('emails.coupons.report');
    }
}