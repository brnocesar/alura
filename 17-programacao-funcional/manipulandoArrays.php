<?php

$dados = require 'dados.php';

// $numeroPaises = count($dados);
// echo "Número de países: $numeroPaises\n";

function convertePaisParaMaiuscula(array $pais) {
    $pais['pais'] = strtoupper($pais['pais']);
    return $pais;
}

function paisComEspacoNoNome(array $pais): bool {
    return strpos($pais['pais'], ' ') !== false;
}

function medalhasPorPais(int $medalhasAcumuladas, int $medalhas) {
    return $medalhasAcumuladas += $medalhas;
}

function totalMedalhas(int $medalhasAcumuladas, array $pais) {
    return $medalhasAcumuladas + array_reduce($pais['medalhas'], 'medalhasPorPais', 0);
}

// $dados = array_map('convertePaisParaMaiuscula', $dados);
// // $dados = array_filter($dados, 'paisComEspacoNoNome');
// echo array_reduce($dados, 'totalMedalhas', 0); // 36

usort($dados, function (array $primeiro, array $segundo) {
    $medalhasPrimeiro = $primeiro['medalhas'];
    $medalhasSegundo  = $segundo['medalhas'];

    $ouro = $medalhasSegundo['ouro'] <=> $medalhasPrimeiro['ouro'];
    $prata = $medalhasSegundo['prata'] <=> $medalhasPrimeiro['prata'];
    return $ouro !== 0 ? $ouro
        : ($prata !== 0 ? $prata
        : $medalhasSegundo['bronze'] <=> $medalhasPrimeiro['bronze']);
});


var_dump($dados);