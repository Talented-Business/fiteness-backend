<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyNewCustomer extends Mailable
{
    use Queueable, SerializesModels;
    public $first_name;
    public $last_name;
    public $email;
    public $gender;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name,$last_name,$email,$gender)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->gender = $gender;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "New Customer";
        return $this->subject($subject)->view('emails.customers.create');
    }
}
