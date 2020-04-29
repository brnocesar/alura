<?php

class ContaCorrente{

    private $titular;
    private $agencia;
    private $numero;
    private $saldo;

    public function __construct($titular, $agencia, $numero, $saldo){
        $this->titular = $titular;
        $this->agencia = $agencia;
        $this->numero = $numero;
        $this->saldo = $saldo;
        
        echo " |> Instancia objeto '" . $this->titular . "' <|";
    }

    public function __get($atributo){
        Validacao::protegeAtributo($atributo);
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        Validacao::protegeAtributo($atributo);
        $this->$atributo = $valor;
    }

    public function sacar($valor){

        Validacao::verificaNumerico($valor);

        $this->saldo = $this->saldo - $valor;
        return $this;
    }

    public function depositar($valor){
        
        Validacao::verificaNumerico($valor);

        $this->saldo = $this->saldo + $valor;
        return $this;
    }

    public function transferir($valor, ContaCorrente $conta){
        
        Validacao::verificaNumerico($valor);
        
        $this->sacar($valor);
        $conta->depositar($valor);
        return $this;
    }

    private function formataSaldo(){
        return "R$ " . number_format($this->saldo, 2, ",", ".");
    }

    public function getSaldo(){
        return $this->formataSaldo();
    }

    public function getTitular(){
        return $this->titular;
    }
    
    // public function getAgencia(){
    //     return $this->agencia;
    // }

    // public function getNumero(){
    //     return $this->numero;
    // }
    

    // public function setNumero($new_numero){
    //     $this->numero = $new_numero;
    // }

    public function __toString(){
        return "<br><br>O titular da conta é " . $this->titular . ".<br>Seu saldo é " . $this->getSaldo() . ".";
    }

    
}

?>