<?php 

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Infra\EntityManagerCreator;

class Persistencia implements InterfaceControladorRequisicao
{
    private $entityManager;

    public function __construct()
    {
        $this->entityManager = (new EntityManagerCreator())->getEntityManager();
    }


    public function processaRequisicao(): void
    {
        // 1) pegar dados do formulário: $_REQUEST, $_POST...
        $descricao = filter_input(
            INPUT_POST, 
            'descricao',
            FILTER_SANITIZE_STRING
        );
        $id = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        // 2) montar modelo Curso
        $curso = new Curso();
        $curso->setDescricao($descricao);

        if ( $id) {
            // 3.1) atualiza
            $curso->setId($id);
            $this->entityManager->merge($curso);
        }
        else {
            // 3.2) cria
            $this->entityManager->persist($curso);
        }

        $this->entityManager->flush(); // persiste no Banco

        header('Location: /listar-cursos', false, 302);
    }
}