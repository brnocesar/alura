<?php

$nomeArquivo = 'arquivos/novo.txt';

/* 1 */
// abre arquivo apenas em modo escrita, posiciona o ponteiro no início
$arquivo = fopen($nomeArquivo, 'w');

$texto = "Texto para ser colocado no novo arquivo como teste. ";

fwrite($arquivo, $texto);

fclose($arquivo);

echo "1) Primeira adição de texto:" . PHP_EOL . file_get_contents($nomeArquivo) . PHP_EOL;


/* 2 */
// abre arquivo em modo escrita apenas, posiciona ponteiro no final do arquivo
$arquivo = fopen($nomeArquivo, 'a');

$maisTexto = "\nMais texto para ser colocado no novo arquivo.xxxxxxx";

fwrite($arquivo, $maisTexto, 45);

fclose($arquivo);

echo "2) Segunda adição de texto:" . PHP_EOL . file_get_contents($nomeArquivo) . PHP_EOL;


/* 3 */
// joga toda a string de $texto no arquivo, com o ponteiro no inicio do arquivo
file_put_contents($nomeArquivo, $texto);

echo "3) jogando tudo no arquivo de uma vez:" . PHP_EOL . file_get_contents($nomeArquivo) . PHP_EOL;


/* 4 */
// adiciona toda a string de $maisTexto no arquivo
file_put_contents($nomeArquivo, $maisTexto, FILE_APPEND);

echo "4) jogando (mais conteúdo) no arquivo de uma vez, usando UMA flag:" . PHP_EOL . file_get_contents($nomeArquivo) . PHP_EOL;
