<?php

// require 'excecao/SaldoInsuficienteExcecao.php';

use excecao\OperacaoNaoRealizadaExcecao;

class ContaCorrente{

    private $titular;
    private $agencia;
    private $numero;
    private $saldo;
    public static $totalContas;
    public static $taxaOperacao;
    public $totalSaquesNaoPermitidos;
    public static $totalOperacoesNaoRealizadas;

    public function __construct($titular, $agencia, $numero, $saldo){
        $this->titular = $titular;
        $this->agencia = $agencia;
        $this->numero = $numero;
        $this->saldo = $saldo;
        $this->totalSaquesNaoPermitidos = 0;
        
        self::$totalContas++;

        try {

            if ( self::$totalContas < 1 ) {
                throw new Exception("Número de contas menor que UM!");
            }

            self::$taxaOperacao = 30 / self::$totalContas;

        } catch( Exception $excecao ){
            echo "Não é possível realizar divisão por zero:\n- - - - - - - -\n$excecao\n- - - - - - - -\n";
            echo "\n\n";
            echo $excecao->getMessage();
            exit;
        }
        
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

        Validacao::verificaValor($valor);

        if ( $valor > $this->saldo ) {
            throw new excecao\SaldoInsuficienteExcecao("Valor superior ao saldo.", $valor, $this->saldo);
        }

        $this->saldo = $this->saldo - $valor;
        return $this;
    }

    public function depositar($valor){
        
        Validacao::verificaValor($valor);

        $this->saldo = $this->saldo + $valor;
        return $this;
    }

    public function transferir($valor, ContaCorrente $conta){
        
        try {
            $arquivo = new LeitorArquivo("logTransferencia.txt");
            $arquivo->abrirArquivo();
            $arquivo->escreverNoArquivo();
            
            Validacao::verificaValor($valor);
            
            $this->sacar($valor);
            $conta->depositar($valor);

            return $this;
            
        } catch (Exception $e) {

            ContaCorrente::$totalOperacoesNaoRealizadas++;
            throw new OperacaoNaoRealizadaExcecao("Operação não realizada", 666, $e);

        } finally {

            $arquivo->fechandoArquivo();

        }
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

    public function __toString(){
        return "<br><br>O titular da conta é " . $this->titular . ".<br>Seu saldo é " . $this->getSaldo() . ".";
    }

    
}

?>