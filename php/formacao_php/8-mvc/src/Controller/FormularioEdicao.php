<?php 

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\RenderizadorDeHtmlTrait;
use Alura\Cursos\Infra\EntityManagerCreator;

class FormularioEdicao implements InterfaceControladorRequisicao
{
    use RenderizadorDeHtmlTrait;

    private $repositorioCursos;

    public function __construct()
    {
        $entityManager = (new EntityManagerCreator())->getEntityManager();
        $this->repositorioCursos = $entityManager->getRepository(Curso::class);
    }

    public function processaRequisicao(): void
    {
        $id = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if ( !$id ) {
            header('Location: /listar-cursos');
            return;
        }
        
        $curso = $this->repositorioCursos->find($id);
        echo $this->renderizaHtml('cursos/formulario-curso', [
            'curso'     => $curso,
            'titulo'    => "Editar curso '{$curso->getDescricao()}'"
        ]);
    }
}