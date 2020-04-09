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

        // 2) montar modelo Curso
        $curso = new Curso();
        $curso->setDescricao($descricao);

        // 3) Inserir no Banco
        $this->entityManager->persist($curso);
        $this->entityManager->flush();

        header('Location: /listar-cursos', false, 302);
    }
}