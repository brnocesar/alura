<?php

class LeitorArquivo {

    private $arquivo;

    public function construct($arquivo){
        $this->arquivo = $arquivo;
    }

    public function abrirArquivo(){
        echo "\n(*) Abrindo arquivo.";
    }

    public function lendoArquivo(){
        echo "\n(*) Lendo arquivo.";
    }

    public function escreverNoArquivo(){
        echo "\n(*) Escrevendo no arquivo.";
    }

    public function fechandoArquivo(){
        echo "\n(*) Fechando arquivo.";
    }
}

?>