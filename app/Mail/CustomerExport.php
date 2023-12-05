<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerExport extends Mailable
{
    use Queueable, SerializesModels;

    public $files;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($files)
    {
        $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = date("Y-m-d").' Customers Export';
        $email =  $this->subject($subject)->view('emails.customers.export');
        foreach($this->files as $index=>$attachment){
            $email->attach($attachment, [
                'as' => "customers$index.xlsx",
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }
        return $email;
    }
}
