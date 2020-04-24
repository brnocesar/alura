<?php

namespace App\Service;

class CriadorDeSerie
{
    public function criarSerie()
    {
        $serie = Serie::create($request->all());

        for ($i = 1; $i <= $request->qtd_temporadas; $i++) {
            $temporada = $serie->temporadas()->create(['numero' => $i]);

            for ($j=1; $j <= $request->ep_por_temporada; $j++) {
                $temporada->episodios()->create(['numero' => $j]);
            }
        }
    }
}
