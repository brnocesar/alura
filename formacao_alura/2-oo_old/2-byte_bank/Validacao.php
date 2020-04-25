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

    public static function verificaNumerico($valor){
        if( !is_numeric($valor) ){
            throw new Exception("O valor passado não é um número");
        }
    }

}

?>