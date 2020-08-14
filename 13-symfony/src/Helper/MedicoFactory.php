<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory
{
    private $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function storeMedico(string $json): Medico
    {
        $request = json_decode($json);

        $especialidade = $this->especialidadeRepository->find($request->especialidadeId);
        
        $medico = new Medico();
        $medico
            ->setCrm($request->crm)
            ->setNome($request->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }
}

