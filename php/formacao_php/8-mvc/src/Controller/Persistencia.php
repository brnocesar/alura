<?php 

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Infra\EntityManagerCreator;

class Persistencia implements InterfaceControladorRequisicao
{
    use FlashMessageTrait;

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

            $curso->setId($id);
            $this->entityManager->merge($curso);
            $this->defineMensagem('success', 'Curso atualizado!');
        }
        else {

            $this->entityManager->persist($curso);
            $this->defineMensagem('success', 'Novo Curso adicionado!');
        }

        $this->entityManager->flush();

        header('Location: /listar-cursos', false, 302);
    }
}