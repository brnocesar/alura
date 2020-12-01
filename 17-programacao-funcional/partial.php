<?php

function dividir($a, $b) {
    return $a / $b;
}

// high order function
function dividirPor2() {
    return function ($dividendo) {
        return dividir($dividendo, 2);
    };
}

// high order function
function dividirPor(int $divisor): callable {
    return function ($dividendo) use ($divisor) {
        return dividir($dividendo, $divisor);
    };
}

echo dividir(4, 2) . PHP_EOL;
echo dividirPor2()(4) . PHP_EOL;
echo dividirPor(2)(4) . PHP_EOL;

// partial application
$dividirPor2 = dividirPor(2); 
echo $dividirPor2(4) . PHP_EOL;
