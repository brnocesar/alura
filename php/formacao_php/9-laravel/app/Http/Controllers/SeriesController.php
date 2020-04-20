<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesFormRequest;
use App\Serie;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $series = Serie::query()->orderBy('nome')->get();
        $mensagem = $request->session()->get('mensagem');

        return view('series.index', compact('series', 'mensagem'));
    }

    public function create(Request $request)
    {
        $mensagem = $request->session()->get('mensagem');
        return view('series.create', compact('mensagem'));
    }

    public function store(SeriesFormRequest $request)
    {
        $serie = Serie::create($request->all());

        for ($i = 1; $i <= $request->qtd_temporadas; $i++) {
            $temporada = $serie->temporadas()->create(['numero' => $i]);

            for ($j=1; $j <= $request->ep_por_temporada; $j++) {
                $temporada->episodios()->create(['numero' => $j]);
            }
        }

        $request->session()->flash(
            'mensagem',
            "Série $serie->nome adicionada com sucesso! ($serie->id)"
        );

        return redirect()->route('listar_series');
    }

    public function destroy(Request $request){
        Serie::destroy($request->id);

        $request->session()->flash(
            'mensagem',
            "Série removida com sucesso!"
        );

        return redirect()->route('listar_series');
    }
}
