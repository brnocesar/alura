<?php

namespace App\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    private $content;
    private $currentPage;
    private $itensPerPage;
    private $totalItens;

    public function __construct($content, int $currentPage, int $itensPerPage, int $totalItens)
    {
        $this->content      = $content;
        $this->currentPage  = $currentPage;
        $this->itensPerPage = $itensPerPage;
        $this->totalItens = $totalItens;
    }

    public function getResponse(): Response
    {
        $response = [
            'dados'          => $this->content,
            'paginaAtual'    => $this->currentPage,
            'itensPorPagina' => $this->itensPerPage,
            'totalItens'     => $this->totalItens
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }
}

