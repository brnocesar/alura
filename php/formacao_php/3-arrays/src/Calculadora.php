<?php

namespace Alura;

class Calculadora {

    public function calculaMedia(array $vetor): ?float {
        $tamanho = sizeof($vetor);

        if ( !$tamanho ) {
            return null;
        }

        // $soma = array_sum($vetor);
        $soma = $media = 0;
        for ($i=0; $i<$tamanho; $i++) {
            $soma += $vetor[$i];
            $media = $soma / ($i + 1);
        }

        return $media;
    }

}


?>