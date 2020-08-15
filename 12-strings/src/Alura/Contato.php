<?php
namespace App\Alura;

class Contato
{
    private $endereco;
    private $cep;
    private $telefone;

    public function __construct(string $endereco, string $cep, string $telefone)
    {
        $this->setEndereco($endereco);
        $this->setCep($cep);
        $this->setTelefone($telefone);
    }

    public function setEndereco($endereco): self
    {
        $this->endereco = $endereco;
        return $this;
    }

    public function getEndereco(): string
    {
        return $this->endereco;
    }

    public function setCep($cep): self
    {
        $this->cep = $cep;
        return $this;
    }

    public function getEnderecoCep(): string
    {
        return implode(" - ", [$this->endereco, $this->cep]);
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public function setTelefone($telefone): self
    {
        $this->telefone = $this->validaTelefone($telefone) ? $telefone : "(*) telefone inválido";
        return $this;
    }

    public function validaTelefone(string $telefone): int
    {
        return preg_match("/^[0-9]{4}-[0-9]{4}$/", $telefone);
    }
}

