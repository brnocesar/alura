<?php

function sacar(array $conta, float $valor): array{
    if($conta['saldo'] < $valor){
        exibeMensagem('Saldo insuficiente.');
        return $conta;
    }
    else{
        $conta['saldo'] -= $valor;
        return $conta;
    }
}

function depositar(array $conta, float $valor): array{
    if($valor > 0){
        $conta['saldo'] += $valor;
        return $conta;
    }
    else{
        exibeMensagem('Depósitos precisam ser de valores positivos.');
        return $conta;
    }

}

function exibeMensagem($mensagem) {
    echo $mensagem . '<br>';
}

function titularMaiusculo(array &$conta){
    $conta['titular'] = strtoupper( $conta['titular'] );
}

function exibeConta(array $conta){
    ['titular' => $titular, 'saldo' => $saldo] = $conta;
    echo '<li>Titular: $titular. Saldo: $saldo</li>';
}
