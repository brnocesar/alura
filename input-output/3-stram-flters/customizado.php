<?php

require 'MeuFiltroLaravel.php';

$local = __DIR__ . '/../arquivos/lista-cursos.txt';

$arquivo = fopen($local, 'r');

// Filtro customizado
stream_filter_register('alura.laravel', MeuFiltroLaravel::class);
stream_filter_append($arquivo, 'alura.laravel');

echo "Linhas com a palavra 'laravel':" . PHP_EOL . fread($arquivo, filesize($local)) . PHP_EOL;