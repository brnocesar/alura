<?php

use Alura\Banco\Modelo\Conta\{ContaPoupanca, ContaCorrente, Titular};
use Alura\Banco\Modelo\{CPF, Endereco, NomeMuitoCurtoException};

require_once 'autoload.php';

$conta = new ContaCorrente(
    new Titular(
        new CPF('123.456.789-10'),
        'Vinicius Dias',
        new Endereco('Petrópolis', 'bairro Teste', 'Rua lá', '37')
    )
);

try {
    
    $conta->deposita(100);

} catch (InvalidArgumentException $exception) {
    
    echo "Valor a depositar precisa ser positivo." . PHP_EOL;
}

echo $conta->recuperaSaldo() . PHP_EOL;