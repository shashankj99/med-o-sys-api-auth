<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\AuthObserver;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\ExampleEvent::class => [
            \App\Listeners\ExampleListener::class,
        ],
    ];

    public function boot()
    {
        User::observe(AuthObserver::class);
    }
}
