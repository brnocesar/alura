<?php

namespace Alura;

class ArrayUtils {

    public static function remover($elemento, array &$vetor): void{
        $posicao = array_search($elemento, $vetor, true);
        if ( is_int($posicao) ) {
            unset($vetor[$posicao]);
            return;
        }
        echo "Elemento não encontrado.";
        return;
    }


    public static function saldoMaiorQue(int $saldo, array $vetor): array{

        foreach ( $vetor as $chave => $valor ) {
            if ( $valor < $saldo ) {
                unset($vetor[$chave]);
            }
        }
        return array_keys($vetor);
    }

}

?>