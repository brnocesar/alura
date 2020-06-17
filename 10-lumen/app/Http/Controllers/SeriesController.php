<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index()
    {
        return Serie::all();
    }

    public function store(Request $request)
    {
        return response()->json(
            Serie::create($request->all()),
            201
        );
    }

    public function show(int $id)
    {
        $serie = Serie::find($id);

        if ( $serie ) {

            return response()->json($serie , 200);
        }

        return response()->json([], 204);
    }

    public function update(int $id, Request $request)
    {
        $serie = Serie::find($id);

        if ( !$serie ) {

            return response()->json(['erro' => 'recurso não encontrado'], 404);
        }

        // $serie->fill($request->all());
        $serie->update($request->all());

        return response()->json($serie , 200);
    }

    public function destroy(int $id)
    {
        $recurso = Serie::destroy($id);

        if ( $recurso == 0 ) {

            return response()->json(['erro' => 'recurso não encontrado'], 404);
        }

        return response()->json([] , 204);
    }
}
