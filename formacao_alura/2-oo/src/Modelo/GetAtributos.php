<?php

namespace Alura\Banco\Modelo;

trait GetAtributos {
    
    public function __get(string $atributo){

        $metodo = 'get' . ucfirst($atributo);
        return $this->$metodo();
    }
}

?>