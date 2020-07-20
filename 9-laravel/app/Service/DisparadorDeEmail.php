<?php

namespace App\Service;

use App\Mail\NovaSerie;
use App\Serie;
use Illuminate\Support\Facades\Mail;

class DisparadorDeEmail
{
    public function enviaEmailNovaSerie(Serie $novaSerie): void
    {
        $email = new NovaSerie($novaSerie->nome);

        Mail::to(auth()->user())->send($email);

        return;
    }
}
