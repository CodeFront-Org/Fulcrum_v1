<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $adminName;
    public $tempPassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $adminName, $tempPassword)
    {
        $this->user = $user;
        $this->adminName = $adminName;
        $this->tempPassword = $tempPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.password_changed')
            ->subject('Security Alert: Your Password has been Reset');
    }
}
