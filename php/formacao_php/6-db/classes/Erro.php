<?php

class Erro
{
    public static function trataErro(Exception $erro)
    {
        if (DEBUG) {
            echo '<pre>';
            print_r( $erro );
            echo '</pre>';
        }
        else {
            echo $erro->getMessage();
        }
        exit;
    }
}