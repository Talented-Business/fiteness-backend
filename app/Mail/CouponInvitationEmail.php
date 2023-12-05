<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Coupon;

class CouponInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $url;
    public $expiration;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id,$expiration)
    {
        $this->url = config('app.url').Coupon::EMAIL_INVITATION_URL.$id;
        if($expiration)$this->expiration = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d de %B del %Y", strtotime($expiration))));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Invitación a Fitemos";
        return $this->subject($subject)->from($address = 'hola@fitemos.com', $name = 'Marifer de Fitemos')->view('emails.coupons.email_invitation');
    }
}