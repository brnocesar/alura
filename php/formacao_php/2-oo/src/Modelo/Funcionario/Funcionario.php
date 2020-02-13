<?php

namespace Alura\Banco\Modelo\Funcionario;

use Alura\Banco\Modelo\Pessoa;
use Alura\Banco\Modelo\Cpf;

abstract class Funcionario extends Pessoa{
    protected float $salario;

    public function __construct(string $nome, Cpf $cpf, float $salario){
        parent::__construct($nome, $cpf);
        $this->salario = $salario;
    }

    public function getCargo(): string {
        return $this->cargo;
    }

    public function getSalario(): string {
        return $this->salario;
    }

    public function recebeAumento(float $valor){
        
        if ( $valor < 0 ) {
            echo "Aumento de ve ser positivo.\n";
            return;
        }
        
        $this->salario += $valor;
    }

    public function setNome(string $nome){
        $this->validaNome($nome);
        $this->nome = $nome;
    }

    abstract public function calculaBonificacao(): float;
}

?>