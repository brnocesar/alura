<?php

namespace Alura\Banco\Modelo;

use Alura\Banco\Modelo\Cpf;

/**
 * @property-read string $nome
 * @property-read Cpf $cpf
 */
abstract class Pessoa {

    use GetAtributos;

    protected string $nome;
    private Cpf $cpf;

    public function __construct(string $nome, Cpf $cpf){
        $this->validaNome($nome);
        $this->nome = $nome;
        $this->cpf = $cpf;
    }

    public function getNome(): string{
        return $this->nome;
    }

    public function getCpf(): string{
        return $this->cpf->getCpf();
    }

    final protected function validaNome(string $nome){
        if ( strlen($nome) < 5 ) {
            echo "Nome precisa ter mais de 5 caracteres.\n";
            exit();
        }
    }
}

?>