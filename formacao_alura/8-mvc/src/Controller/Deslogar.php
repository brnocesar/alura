<?php 

namespace Alura\Cursos\Controller;

use Alura\Cursos\Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Deslogar implements RequestHandlerInterface
{
    use FlashMessageTrait;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        session_destroy();
        
        session_start();
        $this->defineMensagem('success', 'Logout efetuado!');

        return new Response(302, ['Location' => '/login']);
    }
}