<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $repository;
    }

    /**
     * @Route("/especialidades", name="index-especialidades", methods={"GET"})
     */
    public function index(): Response
    {
        $especialidades = $this->repository->findAll();
        
        return new JsonResponse($especialidades, Response::HTTP_OK);
    }

    /**
     * @Route("/especialidades", name="store-especialidades", methods={"POST"})
     */
    public function store(Request $request): Response
    {
        $body = json_decode($request->getContent());

        $especialidade = new Especialidade();
        $this->entityManager->persist($especialidade);
        
        $especialidade->setDescricao($body->descricao);
        $this->entityManager->flush();

        return new JsonResponse($especialidade, Response::HTTP_CREATED);
    }

    /**
     * @Route("/especialidades/{id}", name="show-especialidades", methods={"GET"})
     */
    public function show($id): Response
    {
        $especialidade = $this->repository->find($id);
        $codigoRetorno = is_null($especialidade) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK;
        
        return new JsonResponse($especialidade, $codigoRetorno);
    }

    /**
     * @Route("/especialidades/{id}", name="update-especialidades", methods={"PUT"})
     */
    public function update($id, Request $request): Response
    {
        $body = json_decode($request->getContent());

        $especialidade = $this->repository->find($id);
        $especialidade->setDescricao($body->descricao);

        $this->entityManager->flush();

        return new JsonResponse($especialidade, Response::HTTP_OK);
    }

    /**
     * @Route("/especialidades/{id}", name="destroy-especialidades", methods={"DELETE"})
     */
    public function destroy($id): Response
    {
        $especialidade = $this->repository->find($id);

        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
