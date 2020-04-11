<?php 

namespace Alura\Cursos\Controller;

use Alura\Cursos\Helper\FlashMessageTrait;

class Deslogar implements InterfaceControladorRequisicao
{
    use FlashMessageTrait;

    public function processaRequisicao(): void
    {
        session_destroy();
        
        session_start();
        $this->defineMensagem('success', 'Logout efetuado!');

        header('Location: /login');
    }
}