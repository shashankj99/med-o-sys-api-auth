<?php

namespace App\Jobs;

use App\Mail\ActivationMail;
use Illuminate\Support\Facades\Mail;

class SendActivationMailJob extends Job
{
    /**
     * @var
     */
    protected $user;

    /**
     * SendActivationMailJob constructor.
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
            ->queue(new ActivationMail($this->user));
    }
}
