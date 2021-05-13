<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct( $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        // get the reset token
        $token = $this->user->verificationToken()
            ->where('type', 'reset')
            ->first();

        return $this->subject('Reset Your Password')
            ->view('reset_password')
            ->with(['token' => $token]);
    }
}
