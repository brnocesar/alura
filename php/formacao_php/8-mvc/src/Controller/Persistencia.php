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
        //$descricao = $_POST['descricao'];

        // 2) montar modelo Curso
        $curso = new Curso();
        $curso->setDescricao($_POST['descricao']);

        // 3) Inserir no Banco
        $this->entityManager->persist($curso);
        $this->entityManager->flush();
    }
}