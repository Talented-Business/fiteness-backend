<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    public $username;
    public $content;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$username,$message)
    {
        $this->email = $email;
        $this->username = $username;
        $this->content = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "From ".$this->username."(".$this->email.") message ";
        $sentences = explode("\n",$this->content);
        foreach($sentences as $index=>$sentence){
            if(trim($sentences[$index])!=="")$sentences[$index] = "<p>".$sentences[$index]."</p>";
        }
        $this->content = implode("\n",$sentences);
        return $this->subject($subject)->view('emails.contact.message');
    }
}