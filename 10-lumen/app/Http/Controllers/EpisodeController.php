<?php

namespace App\Http\Controllers;

use App\Models\Episode;

class EpisodeController extends NewBaseController
{
    public function __construct()
    {
        $this->classe = Episode::class;
    }
}
