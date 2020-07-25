<?php

namespace App\Providers;

use App\Events\ApagaSerieEvent;
use App\Events\NovaSerieEvent;
use App\Listeners\NovaSerieEmailListener;
use App\Listeners\NovaSerieLogListener;
use App\Listeners\RemoveImagemCapaListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NovaSerieEvent::class => [
            NovaSerieEmailListener::class,
            NovaSerieLogListener::class
        ]/* ,
        ApagaSerieEvent::class => [
            RemoveImagemCapaListener::class
        ] */
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
