<?php

function retornaString(): string
{
    return 'Olá mundo!';
}

function recebeFuncao(callable $funcao): void
{
    echo 'Executando a função recebida: ';
    echo $funcao();
    echo PHP_EOL;
}

recebeFuncao('retornaString');

recebeFuncao(function () {
    return 'Olá mundo! (passando função anônima)';
});

$funcaoAnonima = function () {
    return 'Olá mundo! (passando função anônima depois de atribuí-la a uma variável)';
};
recebeFuncao($funcaoAnonima);

recebeFuncao(fn () => 'Olá mundo! (passando função anônima em "formato" de arrow function)');
