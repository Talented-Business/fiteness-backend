<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Workout extends Mailable
{
    use Queueable, SerializesModels;
    public $publishDate;
    public $content;
    public $blog;
    public $homeUrl;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($publishDate,$content,$blog)
    {
        $this->publishDate = $publishDate;
        $this->content = $content;
        $this->blog = $blog;
        $this->homeUrl = env('APP_URL');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Fitemos ".$this->publishDate;
        if($this->blog)$subject = "Contenido del ".$this->publishDate;
        $sentences = explode("\n",$this->content[0]);
        foreach($sentences as $index=>$sentence){
            if(trim($sentences[$index])!=="")$sentences[$index] = "<p>".$sentences[$index]."</p>";
        }
        $this->content[0] = implode("\n",$sentences);
        return $this->subject($subject)->view('emails.workouts.send');
    }
}
