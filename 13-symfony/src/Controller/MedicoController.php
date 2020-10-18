<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicoController extends AbstractController
{
    protected $entityManager;
    protected $factory;
    protected $repository;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $factory, MedicoRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function index(): Response
    {
        $medicos = $this->repository->findAll();
        
        return new JsonResponse($medicos, Response::HTTP_OK);
    }

    /**
     * @Route("especialidades/{especialidadeId}/medicos", methods={"GET"})
     */
    public function indexByEspecialidede(int $especialidadeId): Response
    {
        $medicos = $this->repository->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos, Response::HTTP_OK);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $medico = $this->searchMedico($id);

        $codigoRetorno = is_null($medico) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK;
        
        return new JsonResponse($medico, $codigoRetorno); // aqui da erro se nao existe, quando usa getReference
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function store(Request $request): Response
    {
        $medico = $this->factory->createMedico($request->getContent());
        
        $this->entityManager->persist($medico);
        $this->entityManager->flush();
        
        return new JsonResponse($medico, Response::HTTP_CREATED);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        $medico = $this->searchMedico($id);
        if ( is_null($medico) ) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        
        $bodyRequest = json_decode($request->getContent());
        
        $medico->setCrm($bodyRequest->crm)->setNome($bodyRequest->nome); // aqui da erro se nao existe, quando usa getReference
        $this->entityManager->flush();

        return new JsonResponse($medico, Response::HTTP_OK);
    }

    /**
     * @Route("/medicos/{id}", methods={"DELETE"})
     */
    public function destroy(int $id): Response
    {
        $medico = $this->searchMedico($id);
        if ( is_null($medico) ) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        
        $this->entityManager->remove($medico); // aqui NAO da erro se nao existe, quando usa getReference
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function searchMedico(int $id): ?Medico
    {
        // $medico = $this->getDoctrine()->getRepository(Medico::class)->find($id);
        $medico = $this->repository->find($id);
        // $medico = $this->entityManager->getReference(Medico::class, $id); // como valida isso? se nao existe registro, da erro quando acessa o objeto

        return $medico;
    }
}