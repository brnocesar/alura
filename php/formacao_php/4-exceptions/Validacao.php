<?php

class Validacao{

    public static function hello(){
        echo "<br><br>Olá mundo dos métodos estáticos!";
    }

    public static function protegeAtributo($atributo){
        if( $atributo == "titular" or $atributo == "saldo"){
            throw new Exception("O atributo $atributo não pode ser alterado!");
            echo " ##Impossível acessar $atributo## ";
            return false;
        }
    }

    public static function verificaValor($valor){
        if( !is_numeric($valor) ){
            throw new InvalidArgumentException("O valor passado não é um número");
        }
        else if ( $valor < 0 ) {
            throw new Exception("Não são permitidas tranferências de valores negativos.");
        }
    }

    public function verificaValorNegati(){
        
    }

}

?>