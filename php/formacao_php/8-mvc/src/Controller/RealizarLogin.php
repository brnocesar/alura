<?php 

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Usuario;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Infra\EntityManagerCreator;

class RealizarLogin implements InterfaceControladorRequisicao
{
    use FlashMessageTrait;

    /** @var ObjectRepository $repositorioUsuarios */
    private $repositorioUsuarios;

    public function __construct()
    {
        $this->repositorioUsuarios = (new EntityManagerCreator())
            ->getEntityManager()
            ->getRepository(Usuario::class);
    }

    public function processaRequisicao(): void
    {
        $email = filter_input(
            INPUT_POST,
            'email',
            FILTER_VALIDATE_EMAIL
        );
        $senha = filter_input(
            INPUT_POST,
            'senha',
            FILTER_SANITIZE_STRING
        );

        if ( !$email ) {

            $this->defineMensagem('danger', 'O e-mail digitado não é válido!');
            
            header('Location: /login');
            exit();
        }

        /** @var Usuario $usuario */
        $usuario = $this->repositorioUsuarios->findOneBy(['email' => $email]);

        if ( !$usuario or !$usuario->senhaEstaCorreta($senha) ) {
            
            $this->defineMensagem('danger', 'E-mail ou senha inválidos!');
            
            header('Location: /login');
            exit();
        }

        $_SESSION['logado'] = true;
        $this->defineMensagem('success', 'Login efetuado!');

        header('Location: /listar-cursos');
    }
}