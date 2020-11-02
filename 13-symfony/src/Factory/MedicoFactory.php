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

        if ( !isset($request->nome) or !property_exists($request, 'crm') or !isset($request->especialidadeId) ) {
            throw new EntityFactoryException(
                'nome, crm e especialidadeId são campos obrigatórios para cadastrar Médico',
                Response::HTTP_BAD_REQUEST
            );
        }

        $especialidade = $this->especialidadeRepository->find($request->especialidadeId);
        
        $medico = new Medico();
        $medico->setCrm($request->crm)
            ->setNome($request->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }
}
