<?php

$dados = require 'dados.php';

// $numeroPaises = count($dados);
// echo "Número de países: $numeroPaises\n";

function convertePaisParaMaiuscula(array $pais) {
    $pais['pais'] = strtoupper($pais['pais']);
    return $pais;
}

$paisComEspacoNoNome = fn (array $pais): bool => strpos($pais['pais'], ' ') !== false;

$medalhasPorPais = fn (int $medalhasAcumuladas, int $medalhas): int => $medalhasAcumuladas + $medalhas;

$totalMedalhas = fn (int $medalhasAcumuladas, array $pais) => $medalhasAcumuladas + array_reduce($pais['medalhas'], $medalhasPorPais, 0);

$paisesEmLetraMaiuscula = fn ($dados) => array_map('convertePaisParaMaiuscula', $dados);
$removePaisesSemEspacoNoNome = fn ($dados) => array_filter($dados, $paisComEspacoNoNome);

function pipe(callable ...$funcoes): callable 
{
    return fn ($dados) => array_reduce(
        $funcoes, 
        fn ($valorAcumulado, callable $funcaoAtual) => $funcaoAtual($valorAcumulado), 
        $dados
    );
}

$composicaoFuncoes = pipe($removePaisesSemEspacoNoNome, $paisesEmLetraMaiuscula);
$dados = $composicaoFuncoes($dados);
echo array_reduce($dados, $totalMedalhas, 0) . PHP_EOL; // 36

function comparaMedalhas(array $primeiroPais, array $segundoPais): callable
{
    return fn (string $modalidade): int => $segundoPais[$modalidade] <=> $primeiroPais[$modalidade]; // ordem descrescente
}

usort($dados, function (array $primeiro, array $segundo) {
    $comparador = comparaMedalhas($primeiro['medalhas'], $segundo['medalhas']);

    return $comparador('ouro') !== 0 ? $comparador('ouro')
        : ($comparador('prata') !== 0 ? $comparador('prata')
        : $comparador('bronze'));
});


print_r($dados);