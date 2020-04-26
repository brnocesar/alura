<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutenticacaoController extends Controller
{
    public function index()
    {
        return view('autenticacao.index');
    }

    public function entrar(Request $request)
    {
        if ( Auth::check() ) {

            $request->session()->flash('mensagem', "Usuário " . Auth::user()->name . " já está logado");
            return redirect()->route('listar_series');
        }

        if ( !Auth::attempt($request->only('email', 'password')) ) {
            return redirect()->route('listar_series');
        }

        return redirect()->back()->withErrors('Usuário e/ou senha incorretos');
    }

    public function create()
    {
        return view('autenticacao.create');
    }

    public function store(Request $request)
    {

    }
}
