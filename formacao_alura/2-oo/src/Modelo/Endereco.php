<?php

namespace Alura\Banco\Modelo;

/**
 * @property-read string $rua
 * @property-read string $numero
 * @property-read string $bairro
 * @property-read string $cidade
 */
final class Endereco {

    use GetAtributos;
    
    private string $rua;
    private string $numero;
    private string $bairro;
    private string $cidade;

    public function __construct(string $rua, string $numero, string $bairro, string $cidade){
        $this->rua    = $rua;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
    }

    public function getCidade(): string {
        return $this->cidade;
    }

    public function getBairro(): string {
        return $this->bairro;
    }

    public function getRua(): string {
        return $this->rua;
    }

    public function getNumero(): string {
        return $this->numero;
    }

    public function __toString(): string {
        return "{$this->rua}, {$this->numero}, {$this->bairro}, {$this->cidade}";
    }

    
}

?>