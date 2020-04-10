<?php 

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Usuario;
use Alura\Cursos\Infra\EntityManagerCreator;

class RealizarLogin implements InterfaceControladorRequisicao
{
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
            echo "O e-mail digitado não é válido";
            exit();
        }

        /** @var Usuario $usuario */
        $usuario = $this->repositorioUsuarios->findOneBy(['email' => $email]);

        if ( !$usuario or !$usuario->senhaEstaCorreta($senha) ) {
            echo "E-mail ou senha inválidos";
            exit();
        }

        header('Location: /listar-cursos');
    }
}