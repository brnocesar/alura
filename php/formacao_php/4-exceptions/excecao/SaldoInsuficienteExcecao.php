<?php

namespace excecao;

// use Exception;

class SaldoInsuficienteExcecao extends \Exception {

    private $valor;
    private $saldo;
    
    public function __construct($mensagem, $valor=null, $saldo=null){
        
        $this->valor = $valor;
        $this->saldo = $saldo;
        parent::__construct($mensagem);
    }

    public function __get($parametro) {
        return $this->$parametro;
    }
}

?>