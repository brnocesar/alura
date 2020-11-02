<?php

namespace App\Factory;

use App\Entity\Especialidade;
use Symfony\Component\HttpFoundation\Response;

class EspecialidadeFactory implements EntityFactory
{
    public function createEntity(string $json): Especialidade
    {
        $request = json_decode($json);

        if ( !property_exists($request, 'descricao') ) {
            throw new EntityFactoryException(
                'campo descricao é obrigatório para cadastrar Especialidade',
                Response::HTTP_BAD_REQUEST
            );
        }
        
        $especialidade = new Especialidade();
        $especialidade->setDescricao($request->descricao);

        return $especialidade;
    }
}
