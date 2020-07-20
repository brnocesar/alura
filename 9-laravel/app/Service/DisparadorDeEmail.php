<?php

namespace App\Service;

use App\Mail\NovaSerie;
use App\Serie;
use App\User;
use Illuminate\Support\Facades\Mail;

class DisparadorDeEmail
{
    public function enviaEmailNovaSerie(Serie $novaSerie): void
    {
        User::all()->each(function (User $usuario) use ($novaSerie) {

            $email = new NovaSerie($novaSerie->nome);

            Mail::to($usuario)->send($email);
        });

        return;
    }
}
