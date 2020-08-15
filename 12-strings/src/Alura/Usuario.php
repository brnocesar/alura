<?php

namespace App\Alura;

class Usuario
{
    private $nome;
    private $sobrenome;
    private $email;
    private $senha;
    private $genero;
    private $tratamentoSobrenome;

    public function __construct(string $nome, string $email, string $senha, string $genero)
    {
        // $this->setNomeSobrenome($nome);
        // $this->setEmail($this->validaEmail($email));
        // $this->setSenha($senha);
        // $this->setGenero($genero);
        // $this->setTratamentoSobrenome();
        $this->setNomeSobrenome($nome)
            ->setEmail($this->validaEmail($email))
            ->setSenha($senha);
        $this->setGenero($genero);
        $this->setTratamentoSobrenome();
    }

    private function setNomeSobrenome($nome): self
    {
        $nomeCompleto = explode(" ", $nome, 2);

        $this->nome      = $nomeCompleto[0] != "" ? $nomeCompleto[0] : "(*) nome inválido";
        $this->sobrenome = $nomeCompleto[1] ?? "(*) sobrenome inválido";
        return $this;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getSobrenome(): ?string
    {
        return $this->sobrenome;
    }

    private function validaSenha(string $senha): bool
    {
        return strlen($senha) >= 6;
    }

    public function setSenha($senha): self
    {
        $senhaTrim = trim($senha);
        $this->senha = $this->validaSenha($senhaTrim) ? $senhaTrim : "(*) senha inválida";
        return $this;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }

    private function validaEmail(string $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function getUsuario(): string
    {
        $posicaoArroba = strpos($this->email, "@");

        return $posicaoArroba === false ? "(*) usuário inválido" :substr($this->email, 0, $posicaoArroba);
    }
    
    private function setGenero($genero)
    {
        $this->genero = $genero;
        return $this;
    }

    public function getGenero(): string
    {
        return $this->genero;
    }

    private function setTratamentoSobrenome(): self
    {
        $nomeCompleto = "$this->nome $this->sobrenome";
        $tratamento = $this->getGenero() == "M" ? "Sr." : "Sra.";
        // var_dump($nomeCompleto, $tratamento, preg_match("/^(\w+)\b/", $nomeCompleto, $found), $found);
        $this->tratamentoSobrenome = preg_replace("/^(\w+)\b/", $tratamento, $nomeCompleto, 1);
        return $this;
    }
    
    public function getTratamentoSobrenome(): string
    {
        return $this->tratamentoSobrenome;
    }
}

