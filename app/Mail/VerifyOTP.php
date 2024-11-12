<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class VerifyOTP extends Mailable
{
    use Queueable, SerializesModels;
    public $recipient;
    public $otp;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp, $recipient)
    {
        $this->recipient = $recipient;
        $this->otp=$otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $otp=$this->otp;
        $email=$this->recipient;
        $name=User::where('email',$email)->pluck('first_name')->first();
        return $this->markdown('emails.otp',compact('otp','name'));
    }
}