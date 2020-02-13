<?php

namespace Alura\Banco\Modelo\Conta;

abstract class Conta {
    private Titular $titular;
    protected float $saldo;
    private static $numeroContas = 0;

    public function __construct(Titular $titular){
        $this->titular = $titular;
        $this->saldo = 0;

        // Conta::$numeroContas++;
        self::$numeroContas++;
    }

    public function __destruct()
    {
        echo "Conta deletada.\n";
        self::$numeroContas--;
    }

    public function getTitularNome(): string {
        return $this->titular->getNome();
    }

    public function getTitularCpf(): string {
        return $this->titular->getCpf();
    }
    
    public function getSaldo(): float {
        return $this->saldo;
    }

    function saca(float $valor): void {
        $valor += $valor * $this->percentualTarifa();
        if ( $valor > $this->saldo ) {
            echo "Saldo indisponível.\n";
            return;
        }
        
        $this->saldo -= $valor;
    }

    public function deposita(float $valor): void {
        if ( $valor < 0 ) {
            echo "Valor precisa se positivo.\n";
            return;
        }
        
        $this->saldo += $valor;
    }

    public static function getNumeroContas(): int {
        return self::$numeroContas;
    }

    abstract protected function percentualTarifa(): float;
}

?>