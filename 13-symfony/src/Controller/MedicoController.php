<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Factory\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MedicoController extends BaseController
{
    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $factory, MedicoRepository $repository)
    {
        parent::__construct($repository, $entityManager, $factory);
    }

    public function indexByEspecialidede(int $especialidadeId): Response
    {
        $medicos = $this->repository->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos, Response::HTTP_OK);
    }

    /**
     * @param Medico $currentEntity
     * @param Medico $newEntity
     */
    public function updateCurrentEntity($currentEntity, $newEntity)
    {
        $currentEntity
            ->setNome($newEntity->getNome())
            ->setCrm($newEntity->getCrm())
            ->setEspecialidade($newEntity->getEspecialidade());
    }
}