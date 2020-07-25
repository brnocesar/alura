<?php

namespace App\Listeners;

use App\Events\ApagaSerieEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class RemoveImagemCapaListener implements ShouldQueue
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
     * @param  ApagaSerieEvent  $event
     * @return void
     */
    public function handle(ApagaSerieEvent $event)
    {
        if ( $event->serie->capa ) {
            Storage::delete($event->serie->capa);
        }
    }
}
