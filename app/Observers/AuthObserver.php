<?php

namespace App\Observers;

use App\Jobs\SendActivationMailJob;
use App\Models\User;

/**
 * Class AuthObserver
 * @package App\Observers
 * @author Shashank Jha
 */
class AuthObserver
{
//    public $afterCommit = true;

    /**
     * Method to dispatch send activation mail job
     * @param User $user
     */
//    public function created(User $user)
//    {
//        dispatch(new SendActivationMailJob($user));
//    }
}
