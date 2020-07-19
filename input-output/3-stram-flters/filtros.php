<?php

$local = __DIR__ . '/../arquivos/lista-cursos.txt';
$arquivo = fopen($local, 'r');

/**
 * O que são os Stream Filters
 * Esses "filtros" podem manipular os dados que estão em stream, ou seja, sendo 
 * lidos (IN) ou escritos (OUT).
 * */ 

echo "Filtros disponíveis:" . PHP_EOL;
var_dump(stream_get_filters()); // retorna os filtros disponíveis
stream_filter_append($arquivo, 'string.toupper');

echo PHP_EOL . "Conteúdo do arquivo todo em caixa alta:" . PHP_EOL . fread($arquivo, filesize($local)) . PHP_EOL;
