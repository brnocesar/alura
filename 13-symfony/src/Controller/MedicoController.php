<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicoController extends AbstractController
{
    protected $entityManager;
    protected $medicoFactory;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $medicoFactory)
    {
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function index(): Response
    {
        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);

        $medicos = $repositorioDeMedicos->findAll();
        
        return new JsonResponse($medicos);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $medico = $this->searchMedico($id);
        $codigoRetorno = is_null($medico) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK;
        
        return new JsonResponse($medico, $codigoRetorno);
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function store(Request $request): Response
    {
        $medico = $this->medicoFactory->storeMedico($request->getContent());

        $this->entityManager->persist($medico);
        $this->entityManager->flush();
        
        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        $bodyRequest = json_decode($request->getContent());

        $medico = $this->searchMedico($id);
        if ( is_null($medico) ) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $medico->crm = $bodyRequest->crm;
        $medico->nome = $bodyRequest->nome;
        $this->entityManager->flush();

        return new JsonResponse($medico);
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
        
        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function searchMedico(int $id): Medico
    {
        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medico = $repositorioDeMedicos->find($id);

        return $medico;
    }
}