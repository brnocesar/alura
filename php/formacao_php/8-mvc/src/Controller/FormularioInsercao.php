<?php 

namespace Alura\Cursos\Controller;

class FormularioInsercao extends ControllerComHtml implements InterfaceControladorRequisicao
{
    public function processaRequisicao(): void
    {
        $this->renderizaHtml('cursos/formulario-curso', [
            'titulo' => 'Novo Curso'
        ]);
    }
}