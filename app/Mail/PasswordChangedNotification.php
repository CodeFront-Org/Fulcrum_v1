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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $adminName)
    {
        $this->user = $user;
        $this->adminName = $adminName;
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
