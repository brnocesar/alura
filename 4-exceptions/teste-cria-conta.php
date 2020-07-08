<?php

use Alura\Banco\Modelo\Conta\{ContaPoupanca, ContaCorrente, Titular};
use Alura\Banco\Modelo\{CPF, Endereco, NomeMuitoCurtoException};

require_once 'autoload.php';

try {

    $conta = new ContaCorrente(
        new Titular(
            new CPF('123.456.789-10'),
            'Vinicius',
            new Endereco('Petrópolis', 'bairro Teste', 'Rua lá', '37')
        )
    );

} catch (InvalidArgumentException $e) {
    
    echo "O formato do CPF é inválido." . PHP_EOL;

} catch (NomeMuitoCurtoException $e) {
    
    echo "O nome deve ter mais caracteres." . PHP_EOL;
}

echo "Executado" . PHP_EOL;