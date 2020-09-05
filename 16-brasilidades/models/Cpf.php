<?php

// require_once "ModeloBasico.php";

class Cpf// extends ModeloBasico
{
    protected string $cpf;

    public function __construct($cpf)
    {
        $this->formatoValido($cpf);
        
        $soNumeros = $this->removeFormatacao($cpf);

        $this->verificaNumerosIguais($soNumeros);

        $this->validaDigitos($soNumeros);

        $this->setCpf($cpf);
    }

    private function formatoValido(string $cpf): void
    {
        // 123.456.789-10
        $padraoCpf = "/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[0-9]{2}$/";

        if (preg_match($padraoCpf, $cpf)) {
            return;
        }
        throw new Exception("CPF inválido. Formato incorreto.");
    }

    private function removeFormatacao(string $cpf): string
    {
        return str_replace([".", "-"], "", $cpf);
    }

    private function verificaNumerosIguais(string $cpf): void
    {
        $tamanho = strlen($cpf);
        for ($i=0; $i <= $tamanho; $i++) {
            if (str_repeat($i, $tamanho) == $cpf) {
                throw new Exception("CPF inválido. Todos números iguais.");
            }
        }
        return;
    }

    private function validaDigitos(string $cpf): void
    {
        $primeiroDigito = 0;
        for ($i=0, $peso=10; $i<=8; $i++, $peso--) {
            $primeiroDigito += $cpf[$i] * $peso;
        }
        $primeiroResto  = $primeiroDigito % 11;
        $primeiroDigito = $primeiroResto < 2 ? 0 : 11 - $primeiroResto;
        if ($primeiroDigito != $cpf[9]) {
            throw new Exception("CPF inválido. Digitos verificadores não correspondem (primeiro).");
        }

        $segundoDigito = 0;
        for ($i=0, $peso=11; $i<=9; $i++, $peso--) {
            $segundoDigito += $cpf[$i] * $peso;
        }
        $segundoResto  = $segundoDigito % 11;
        $segundoDigito = $segundoResto < 2 ? 0 : 11 - $segundoResto;
        if ($segundoDigito != $cpf[10]) {
            throw new Exception("CPF inválido. Digitos verificadores não correspondem (segundo).");
        }

        return;
    }

    
    public function showCpf(): string
    {
        $valorFormatado = substr($this->cpf, 0, 3) . ".";
        $valorFormatado .= substr($this->cpf, 3, 3) . ".";
        $valorFormatado .= substr($this->cpf, 6, 3) . "-";
        $valorFormatado .= substr($this->cpf, 9);
        
        return $valorFormatado;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    private function setCpf($cpf): self
    {
        $this->cpf = $this->removeFormatacao($cpf);
        return $this;
    }
}

