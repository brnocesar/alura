<?php 

$cursos = file('arquivos/lista-cursos.txt');
$formacoes = file('arquivos/lista-formacoes.txt');

$csvFile = fopen('arquivos/alura.csv', 'w');

foreach ( $cursos as $curso ) {

    fwrite($csvFile, implode(',', ["curso", $curso]));
}

foreach ( $formacoes as $formacao ) {

    fputcsv($csvFile, ['formacao', trim($formacao)]);
}

echo "Escreve os cursos e formações em arquivo CSV:" . PHP_EOL . file_get_contents('arquivos/alura.txt') . PHP_EOL;