<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewalPaymentBankReminder extends Mailable
{
    use Queueable, SerializesModels;
    public $firstName;
    public $endDate;
    public $type;
    public $days;
    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstName, $endDate, $type)
    {
        $this->firstName = $firstName;
        // $this->endDate = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d de %B", strtotime($endDate))));
        $this->endDate = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d de %B del %Y", strtotime($endDate))));
        $this->type = $type;
        switch($type){
            case "before_seven":
                $this->days = 7;
                $this->url = config('app.url').'/pricing?renewal=bank';
                break;
            case "before_one":
                $this->days = 1;
                $this->url = config('app.url').'/pricing?renewal=bank';
                break;    
            case "after_one":
                $this->days = 1;
                $this->url = config('app.url').'/pricing';
                break;    
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Tu membresía expiró. Renueva Aquí.";
        $templatePath = "emails.payments.bank_reminder_after";
        switch($this->type){
            case "before_seven":
                $subject = "Tu membresía expira en 7 días. Renueva con anticipación.";
                $templatePath = "emails.payments.bank_reminder_before";
            break;
            case "before_one":
                $subject = "Tu membresía expira en 24 horas. Renueva con anticipación.";
                $templatePath = "emails.payments.bank_reminder_before";
            break;    
            case "after_one":
                $subject = "Tu membresía expiró. Renueva Aquí.";
                $templatePath = "emails.payments.bank_reminder_after";
            break;    
        }
        return $this->subject($subject)->view($templatePath);
    }
}
