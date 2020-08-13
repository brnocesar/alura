<?php

namespace App\Helper;

use App\Entity\Medico;

class MedicoFactory
{
    public function storeMedico(string $json): Medico
    {
        $request = json_decode($json);
        
        $medico = new Medico();
        $medico->crm = $request->crm;
        $medico->nome = $request->nome;

        return $medico;
    }
}

