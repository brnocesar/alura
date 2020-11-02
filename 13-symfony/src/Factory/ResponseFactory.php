<?php

namespace App\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ResponseFactory
{
    /**
     * @var mixed
     */
    private $content;
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var int|null
     */
    private $currentPage;
    /**
     * @var int|null
     */
    private $itensPerPage;
    /**
     * @var int|null
     */
    private $totalItens;

    public function __construct($content, int $statusCode, ?int $currentPage = null, ?int $itensPerPage = null, ?int $totalItens = null)
    {
        $this->content      = $content;
        $this->statusCode   = $statusCode;
        $this->currentPage  = $currentPage;
        $this->itensPerPage = $itensPerPage;
        $this->totalItens   = $totalItens;
    }

    public function getResponse(): Response
    {
        $response = [
            'dados' => $this->content,
        ];

        if ( !is_null($this->currentPage) ) {
            $response['paginaAtual'] = $this->currentPage;
        }

        if ( !is_null($this->itensPerPage) ) {
            $response['itensPorPagina'] = $this->itensPerPage;
        }

        if ( !is_null($this->totalItens) ) {
            $response['totalItens'] = $this->totalItens;
        }

        return new JsonResponse($response, $this->statusCode);
    }

    public static function fromError(Throwable $erro): self
    {
        return new self(
            ['mensagem' => $erro->getMessage()], 
            $erro->getCode() == 0 ? $erro->getStatusCode() : $erro->getCode()
        );
    }
}
