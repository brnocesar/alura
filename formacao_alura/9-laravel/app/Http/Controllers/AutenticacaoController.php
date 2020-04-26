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

            $request->session()->flash('mensagem', "Usuário já está logado");
            return redirect()->route('listar_series');
        }

        if ( !Auth::attempt(['email' => $request->email, 'password' => $request->password]) ) {
            return redirect()->route('listar_series');
        }

        return redirect()->back()->withErrors('Usuário e/ou senha incorretos');
    }
}
