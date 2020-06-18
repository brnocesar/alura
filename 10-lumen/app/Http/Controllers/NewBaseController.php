<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class NewBaseController extends Controller
{
    protected $classe;

    public function index(Request $request)
    {
        return json_encode($this->classe::paginate($request->per_page), JSON_UNESCAPED_SLASHES);
    }

    public function store(Request $request)
    {
        return response()->json(
            $this->classe::create($request->all()),
            201
        );
    }

    public function show(int $id)
    {
        $recurso = $this->classe::find($id);

        if ( $recurso ) {

            return response()->json($recurso , 200);
        }

        return response()->json([], 204);
    }

    public function update(int $id, Request $request)
    {
        $recurso = $this->classe::find($id);

        if ( !$recurso ) {

            return response()->json(['erro' => 'recurso não encontrado'], 404);
        }

        $recurso->update($request->all());

        return response()->json($recurso , 200);
    }

    public function destroy(int $id)
    {
        $recursoRemovido = $this->classe::destroy($id);

        if ( $recursoRemovido == 0 ) {

            return response()->json(['erro' => 'recurso não encontrado'], 404);
        }

        return response()->json([] , 204);
    }
}
