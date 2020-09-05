<?php

// require_once "ModeloBasico.php";

class Cnpj// extends ModeloBasico
{
    protected string $cnpj;

    public function __construct($cnpj)
    {
        $this->formatoValido($cnpj);
        
        $soNumeros = $this->removeFormatacao($cnpj);

        $this->verificaNumerosIguais($soNumeros);

        $this->validaDigitos($soNumeros);

        $this->setCnpj($cnpj);
    }

    private function formatoValido(string $cnpj): void
    {
        // 12.345.678/9123-05
        $padraoCnpj = "/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}\-[0-9]{2}$/";

        if (preg_match($padraoCnpj, $cnpj)) {
            return;
        }
        throw new Exception("CNPJ inválido. Formato incorreto.");
    }

    private function removeFormatacao(string $cnpj): string
    {
        return str_replace([".", "/", "-"], "", $cnpj); // usar um metodo que tire todos os caracteres especias
    }

    private function verificaNumerosIguais(string $cnpj): void
    {
        $tamanho = strlen($cnpj);
        for ($i=0; $i <= $tamanho; $i++) {
            if (str_repeat($i, $tamanho) == $cnpj) {
                throw new Exception("CNPJ inválido. Todos números iguais.");
            }
        }
        return;
    }

    private function validaDigitos(string $cnpj): void
    {
        $primeiroDigito = 0;
        for ($i=0, $peso=5; $i<=11; $i++, $peso--) {
            $peso = $peso < 2 ? 9 : $peso;
            $primeiroDigito += $cnpj[$i] * $peso;
            // echo "=> total1: $cnpj[$i], $peso, $primeiroDigito";
        }
        $primeiroResto  = $primeiroDigito % 11;
        $primeiroDigito = $primeiroResto < 2 ? 0 : 11 - $primeiroResto;
        if ($primeiroDigito != $cnpj[12]) {
            throw new Exception("CNPJ inválido. Digitos verificadores não correspondem (primeiro).");
        }

        $segundoDigito = 0;
        for ($i=0, $peso=6; $i<=12; $i++, $peso--) {
            $peso = $peso < 2 ? 9 : $peso;
            $segundoDigito += $cnpj[$i] * $peso;
        }
        $segundoResto  = $segundoDigito % 11;
        $segundoDigito = $segundoResto < 2 ? 0 : 11 - $segundoResto;
        if ($segundoDigito != $cnpj[13]) {
            throw new Exception("CNPJ inválido. Digitos verificadores não correspondem (segundo).");
        }

        return;
    }

    
    public function showCnpj(): string
    {
        $valorFormatado = substr($this->cnpj, 0, 2) . "." 
            . substr($this->cnpj, 2, 3) . "."
            . substr($this->cnpj, 5, 3) . "/"
            . substr($this->cnpj, 8, 4) . "-"
            . substr($this->cnpj, 12);
        
        return $valorFormatado;
    }

    public function getCnpj(): string
    {
        return $this->cnpj;
    }

    private function setCnpj($cnpj): self
    {
        $this->cnpj = $this->removeFormatacao($cnpj);
        return $this;
    }
}

