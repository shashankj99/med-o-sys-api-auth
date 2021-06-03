<?php

namespace App\Providers;

use App\Http\AuthUser;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('AuthUser', AuthUser::class);
    }
}
