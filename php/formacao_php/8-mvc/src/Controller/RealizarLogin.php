<?php 

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Usuario;
use Alura\Cursos\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RealizarLogin implements RequestHandlerInterface
{
    use FlashMessageTrait;

    /** @var ObjectRepository $repositorioUsuarios */
    private $repositorioUsuarios;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repositorioUsuarios = $entityManager->getRepository(Usuario::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        extract($request->getParsedBody());

        if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {

            $this->defineMensagem('danger', 'O e-mail digitado não é válido!');
            return new Response(302, ['Location' => '/login']);
        }

        /** @var Usuario $usuario */
        $usuario = $this->repositorioUsuarios->findOneBy(['email' => $email]);

        if ( !$usuario or !$usuario->senhaEstaCorreta($senha) ) {
            
            $this->defineMensagem('danger', 'E-mail ou senha inválidos!');
            return new Response(302, ['Location' => '/login']);
        }

        $_SESSION['logado'] = true;
        $this->defineMensagem('success', 'Login efetuado!');

        return new Response(302, ['Location' => '/listar-cursos']);
    }
}