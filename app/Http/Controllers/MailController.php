<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class MailController extends Controller
{
    public function sendEmail()
    {
       // return "Mailll";
        $details = [
            'title' => 'Mail from YourApp',
            'body' => 'This is a test email.'
        ];

            $link=env('APP_URL').'/reset-password/1/123234';
            $recipient="martinnjoroge0028@gmail.com";
            Mail::to($recipient)->send(new ResetPasswordMail($link, $recipient));

        return "Email Sent!";
    }
}
