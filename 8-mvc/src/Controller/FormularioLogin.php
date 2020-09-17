<?php 

namespace Alura\Cursos\Controller;

use Alura\Cursos\Helper\RenderizadorDeHtml;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FormularioLogin implements RequestHandlerInterface
{
    use RenderizadorDeHtml;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $html = $this->renderizaHtml('login/formulario', [
            'titulo' => 'Login'
        ]);

        return new Response(200, [], $html);
    }
}