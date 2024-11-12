<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ApproveLoanMail extends Mailable
{
    use Queueable, SerializesModels;
    public $link;
    public $recipient;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link, $recipient)
    {
        $this->link = $link;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link=$this->link;
        $email=$this->recipient;
        $name=User::where('email',$email)->pluck('first_name')->first();
        return $this->markdown('emails.approve',compact('link','name'));
    }
}