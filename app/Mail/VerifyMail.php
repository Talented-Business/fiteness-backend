<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $customer;
    public $objective;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        if($user->customer){
            $this->customer = $user->customer;
            $this->objective = $this->customer->objective;
            if($this->objective=='auto'){
                $imc = $this->customer->getImc();
                if($imc<18.5){
                    $this->objective = "strong";
                }else if($imc>=25){
                    $this->objective = "cardio";
                }else{
                    $this->objective = "fit";
                }        
            }
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->user->customer){
            $name = $this->user->customer->first_name;
        }else{
            $name = $this->user->name;
        }
        return $this->subject(utf8_encode($name.", ¡Bienvenido a la familia Fitemos!"))->view('emails.auth.welcome', ['user' => $this->user,'name'=>$name]);
    }
}
