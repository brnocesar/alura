<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $usuario = User::where('email', '=', $request->email)->first();

        if ( !$usuario or !Hash::check($request->password, $usuario->password) ) {
            return response()->json(["Não autorizado" => "Usuário e/ou senha inválidos"], 401, [], JSON_UNESCAPED_SLASHES);
        }

        $token = JWT::encode(['email' => $usuario->email], env('JWT_KEY'));

        return [
            'access_token' => $token
        ];
    }
}
