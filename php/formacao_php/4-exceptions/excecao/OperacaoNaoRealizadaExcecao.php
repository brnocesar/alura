<?php

namespace excecao;

use Exception;

class OperacaoNaoRealizadaExcecao extends Exception {
    
    public function __construct($mensagem, $codigo=null, $outraExcecao=null){
        
        parent::__construct($mensagem, $codigo, $outraExcecao);
    }

    public function __get($parametro) {
        return $this->$parametro;
    }

    public function __toString(){
        return $this->getCode() . ": " . $this->getMessage() . ".";
    }
}

?>