<?php

namespace Alura\Banco\Servico;

use Alura\Banco\Modelo\Funcionario\Funcionario;

class ControladorBonificacoes {
    
    private $totalBonificacoes = 0;
    
    public function adicionaBonificacao(Funcionario $funcionario): void {
        $this->totalBonificacoes += $funcionario->calculaBonificacao();
    }

    public function getTotal(): float {
        return $this->totalBonificacoes;
    }
}

?>