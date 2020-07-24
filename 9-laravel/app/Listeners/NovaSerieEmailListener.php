<?php

namespace App\Listeners;

use App\Events\NovaSerieEvent;
use App\Mail\NovaSerieEmail;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NovaSerieEmailListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NovaSerieEvent  $event
     * @return void
     */
    public function handle(NovaSerieEvent $event)
    {
        $nomeDaSerie = $event->nomeDaSerie;
        $multiplicador = 0;
        User::all()->each(function (User $usuario) use ($nomeDaSerie, &$multiplicador) {

            Mail::to($usuario)->later(
                now()->addSeconds(5 * $multiplicador++),
                new NovaSerieEmail($nomeDaSerie)
            );
        });
    }
}
