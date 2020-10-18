<?php

namespace App\Factory;

use App\Entity\Especialidade;

class EspecialidadeFactory implements EntityFactory
{
    public function createEntity(string $json): Especialidade
    {
        $request = json_decode($json);
        
        $especialidade = new Especialidade();
        $especialidade->setDescricao($request->descricao);

        return $especialidade;
    }
}
