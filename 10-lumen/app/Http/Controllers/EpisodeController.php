<?php

namespace App\Http\Controllers;

use App\Models\Episode;

class EpisodeController extends NewBaseController
{
    public function __construct()
    {
        $this->classe = Episode::class;
    }


    public function perSerie(int $serieId)
    {
        $episodios = Episode::where('serie_id', '=', $serieId)->get();

        return json_encode($episodios, JSON_UNESCAPED_SLASHES);
    }
}
