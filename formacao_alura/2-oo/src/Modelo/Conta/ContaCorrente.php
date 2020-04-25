<?php

namespace Alura\Banco\Modelo\Conta;

use Alura\Banco\Modelo\Conta\Conta;

class ContaCorrente extends Conta{

    public function transfere(float $valor, Conta $destino): void {
        if ($valor > $this->saldo) {
            echo "Saldo indisponível.\n";
            return;
        }

        $this->saca($valor);
        $destino->deposita($valor);
    }

    protected function percentualTarifa(): float {
        return 0.05;
    }

}

?>