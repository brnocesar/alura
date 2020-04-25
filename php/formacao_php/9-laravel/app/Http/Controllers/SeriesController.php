<?php

namespace App\Http\Controllers;

use App\Episodio;
use App\Http\Requests\SeriesFormRequest;
use App\Serie;
use App\Service\CriadorDeSerie;
use App\Temporada;
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

    public function store(SeriesFormRequest $request, CriadorDeSerie $criadorDeSerie)
    {
        $serie = $criadorDeSerie->criarSerie($request->nome, $request->qtd_temporadas, $request->ep_por_temporada);

        $request->session()->flash(
            'mensagem',
            "Série $serie->nome adicionada com sucesso! ($serie->id)"
        );

        return redirect()->route('listar_series');
    }

    public function destroy(Request $request)
    {
        $serie = Serie::find($request->id);
        $nomeSerie = $serie->nome;

        $serie->temporadas->each(function (Temporada $temporada){
            $temporada->episodios->each(function (Episodio $episodio){
                $episodio->delete();
            });
            $temporada->delete();
        });
        $serie->delete();

        $request->session()->flash('mensagem', "Série $nomeSerie removida com sucesso!");

        return redirect()->route('listar_series');
    }
}
