<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class RemoveImagemCapaJob
{
    use Dispatchable, Queueable;

    /**
     * @var object
     */
    private $serie;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(object $serie)
    {
        $this->serie = $serie;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ( $this->serie->capa ) {
            Storage::delete($this->serie->capa);
        }
    }
}
