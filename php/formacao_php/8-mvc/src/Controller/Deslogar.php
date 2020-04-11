<?php 

namespace Alura\Cursos\Controller;

class Deslogar implements InterfaceControladorRequisicao
{
    public function processaRequisicao(): void
    {
        session_destroy();
        
        session_start();
        $_SESSION['tipo_mensagem'] = 'success';
        $_SESSION['mensagem'] = "Logout efetuado!";

        header('Location: /login');
    }
}