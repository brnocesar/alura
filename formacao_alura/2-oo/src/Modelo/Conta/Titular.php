<?php

namespace Alura\Banco\Modelo\Conta;

use Alura\Banco\Modelo\{Autenticavel, Pessoa, Endereco, Cpf};

class Titular extends Pessoa implements Autenticavel {
    private Endereco $endereco;

    public function __construct(string $nome, Cpf $cpf, Endereco $endereco){
        parent::__construct($nome, $cpf);
        $this->endereco = $endereco;
    }

    public function getEndereco(): Endereco {
        return $this->endereco;
    }
    public function podeAutenticar(string $senha): bool {
        return $senha === '1425';
    }
}

?>