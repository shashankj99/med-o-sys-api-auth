<?php

namespace App\Jobs;

use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class ResetPasswordJob extends Job
{
    /**
     * @var
     */
    protected $user;

    /**
     * ResetPasswordJob constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Method to queue activation mail and send it to the user
     */
    public function handle()
    {
        Mail::to($this->user->email)
            ->queue(new ResetPasswordMail($this->user));
    }
}
