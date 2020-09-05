<?php

require_once "ModeloBasico.php";
require_once "Cpf.php";
require_once "Cnpj.php";

class Cliente extends ModeloBasico
{
    public $nome;
    private $cpf;
    private $cnpj;
    private $telefone;
    private $email;
    private $cep;
    public $endereco;
    public $bairro;
    public $numero;
    public $cidade;
    public $uf;

    public function __construct(
        string $nome,
        string $cpf,
        string $cnpj,
        string $telefone,
        string $email,
        string $cep,
        string $endereco,
        string $bairro,
        string $numero,
        string $cidade,
        string $uf
    ) {
        $this->nome     = $this->removeFormatacao($nome);
        $this->email    = $this->removeFormatacao($email);
        $this->endereco = $this->removeFormatacao($endereco);
        $this->bairro   = $this->removeFormatacao($bairro);
        $this->numero   = $this->removeFormatacao($numero);
        $this->cidade   = $this->removeFormatacao($cidade);
        $this->uf       = $this->removeFormatacao($uf);

        $this->setCep($cep)
            ->setTelefone($telefone)
            ->setEmail($email)
            ->setCpf($cpf)
            ->setCnpj($cnpj);
    }
    
    public function getCep(): string
    {
        return $this->cep;
    }

    public function showCep(): string
    {
        return substr($this->cep, 0, 2) . "."
            . substr($this->cep, 2, 3) . "-"
            . substr($this->cep, 5);
    }

    public function setCep(string $cep): self
    {
        if (strlen($cep) == 10) {

            // 12.345-678
            if (preg_match("/^[0-9]{2}\.[0-9]{3}\-[0-9]{3}$/", $cep)) {

                $this->cep = $this->removeFormatacao($cep);
                return $this;
            }
        }
        throw new Exception("CEP no formato inválido.");
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public function showTelefone(): string
    {
        return "(" . substr($this->telefone, 0, 2) . ") "
            . substr($this->telefone, 2, 5) . "-"
            . substr($this->telefone, -4);
    }

    public function setTelefone($telefone): self
    {
        if (strlen($telefone) == 15) {

            // (41) 99898-7878
            if (preg_match("/^\([0-9]{2}\)\s[0-9]{5}\-[0-9]{4}$/", $telefone)) {

                $this->telefone = $this->removeFormatacao($telefone);
                return $this;
            }
        }
        throw new Exception("Telefone em formato inválido.");
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        // filter_var($email, FILTER_VALIDATE_EMAIL);
        // batatinha@leguminho.com
        if (preg_match("/^[a-z0-9.]+@[a-z0-9]+.[a-z]+.([a-z]+)?$/", $email)){

            $this->email = $this->removeFormatacao($email);
            return $this;
        }
        throw new Exception("Email não é válido.");
    }

    public function showCpf(): string
    {
        return $this->cpf->showCpf();
    }

    public function getCpf(): string
    {
        return $this->cpf->getCpf();
    }

    public function setCpf($cpf): self
    {
        $this->cpf = new Cpf($cpf);
        return $this;
    }

    public function getCnpjCliente(): Cnpj
    {
        return $this->cnpj;
    }

    public function setCnpj($cnpj): self
    {
        $this->cnpj = new Cnpj($cnpj);
        return $this;
    }
}