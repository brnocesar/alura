<?php

$arquivo = new SplFileObject('arquivos/alura.csv');

while ( !$arquivo->eof() ) {

    $linha = $arquivo->fgetcsv(',');

    echo $linha[1] . PHP_EOL;
}