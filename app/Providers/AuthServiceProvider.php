<?php

namespace App\Providers;

use App\Models\Token;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            // get access token via Authorization Header
            if ($request->header('Authorization')) {
                // get the key
                $key = explode(' ', $request->header('Authorization'));

                return Token::whereToken($key[1])->first();
            }
        });
    }
}
