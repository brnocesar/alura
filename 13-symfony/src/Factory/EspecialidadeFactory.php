<?php

namespace App\Factory;

use App\Entity\Especialidade;
use Symfony\Component\HttpFoundation\Response;

class EspecialidadeFactory implements EntityFactory
{
    public function createEntity(string $json): Especialidade
    {
        $request = json_decode($json);
        $this->checkAllProperties($request);
        
        $especialidade = new Especialidade();
        $especialidade->setDescricao($request->descricao);

        return $especialidade;
    }

    private function checkAllProperties(object $request): void
    {
        if ( !property_exists($request, 'descricao') ) {
            throw new EntityFactoryException(
                'Atributo descricao é obrigatório',
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
