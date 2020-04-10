<?php 

namespace Alura\Cursos\Controller;

abstract class ControllerComHtml
{
    public function renderizaHtml(string $caminhoTemplate, array $dados): string
    {
        extract($dados);

        ob_start();
        require __DIR__ . '/../../view/'. $caminhoTemplate . '.php';
        
        // $html = ob_get_contents();
        // ob_clean();
        // return $html;

        return ob_get_clean();
    }
}