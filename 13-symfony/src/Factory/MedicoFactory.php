<?php

namespace App\Factory;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;
use Symfony\Component\HttpFoundation\Response;

class MedicoFactory implements EntityFactory
{
    private $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function createEntity(string $json): Medico
    {
        $request = json_decode($json);
        $this->checkAllProperties($request);

        $especialidade = $this->especialidadeRepository->find($request->especialidadeId);
        
        $medico = new Medico();
        $medico->setCrm($request->crm)
            ->setNome($request->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }

    private function checkAllProperties(object $request): void
    {
        $statusCode = Response::HTTP_BAD_REQUEST;
        if ( !isset($request->nome) ) {
            throw new EntityFactoryException(
                'Atributo nome é obrigatório',
                $statusCode
            );
        }

        if ( !property_exists($request, 'crm') ) {
            throw new EntityFactoryException(
                'Atributo crm é obrigatório',
                $statusCode
            );
        }

        if ( !isset($request->especialidadeId) ) {
            throw new EntityFactoryException(
                'Atributo especialidadeId é obrigatório',
                $statusCode
            );
        }
    }
}
