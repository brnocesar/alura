<?php

namespace Alura\Banco\Servico;

use Alura\Banco\Modelo\Autenticavel;
use Alura\Banco\Modelo\Funcionario\Diretor;

class Autenticador {
    
    public function tentaLogin(Autenticavel $autenticavel, string $senha): void {
        
        if ( $autenticavel->podeAutenticar($senha) ){
            echo "Ok. Usuário logado no sistema.";
        }
        else {
            echo "Ops. Senha incorreta.";
        }
    }

}

?>