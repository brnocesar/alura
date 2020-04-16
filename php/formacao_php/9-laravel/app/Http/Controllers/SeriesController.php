<?php

namespace App\Http\Controllers;

use App\Serie;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $series = [
            'Terrace House',
            'Peaky Blinders',
            'Stranger Things'
        ];

        return view('series.index', compact('series'));
    }

    public function create(Request $request)
    {
        return view('series.create');
    }

    public function store(Request $request)
    {
        $nome = $request->nome;

        $serie = new Serie();
        $serie->nome = $nome;
        $serie->save();
    }
}
