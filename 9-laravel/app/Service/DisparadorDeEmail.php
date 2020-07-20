<?php

namespace App\Service;

use App\Mail\NovaSerie;
use App\Serie;
use App\User;
use Illuminate\Support\Facades\Mail;

class DisparadorDeEmail
{
    public function enviaEmailNovaSerie(Serie $novaSerie, bool $all=false): void
    {
        $email = new NovaSerie($novaSerie->nome);

        Mail::to(
            $all ? User::all() : auth()->user()
        )->send($email);

        return;
    }
}
