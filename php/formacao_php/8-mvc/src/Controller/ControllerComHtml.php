<?php 

namespace Alura\Cursos\Controller;

abstract class ControllerComHtml
{
    public function renderizaHtml(string $caminhoTemplate, array $dados): void
    {
        extract($dados);

        require __DIR__ . '/../../view/'. $caminhoTemplate . '.php';
    }
}