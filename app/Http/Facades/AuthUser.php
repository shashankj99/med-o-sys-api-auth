<?php

namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class AuthUser extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AuthUser';
    }
}
