<?php

$nomeDoArquivo = 'arquivos/arquivo.txt';

/* 1 */
echo "1) Lendo arquivo linha por linha:" . PHP_EOL;

// abre arquivo apenas em modo leitura e coloca o seu ponteiro ("cursor") no inicio
$arquivo = fopen($nomeDoArquivo, 'r');

// enquanto o ponteiro não estiver no final do arquivo
while ( !feof($arquivo) ) {

    // retorna a primeira linha e coloca o ponteiro no final dessa linha
    $linha = fgets($arquivo);

    echo $linha;
}

// fecha o arquivo
fclose($arquivo);

/* 2 */
echo "2) Lendo todo o arquivo:" . PHP_EOL;

$arquivo = fopen($nomeDoArquivo, 'r');

// numero de caractéres no arquivo
$numeroCaracteres = filesize($nomeDoArquivo);

// retorna o numero de caracteres em uma string
$todasLinhas = fread($arquivo, $numeroCaracteres);

echo $todasLinhas;

fclose($arquivo);

/* 3 */
echo "3) Retorna todo o arquivo, com apenas uma função:" . PHP_EOL;
// retorna todo conteúdo do arquivo em uma string
echo file_get_contents($nomeDoArquivo);

/* 4 */
echo "4) Carregando o arquivo para um array:" . PHP_EOL;
$vetor = file($nomeDoArquivo);

print_r($vetor);

/* 5 */
echo "5) Carregando o retorno de um endpoint para um array, usando o wrapper 'http(s)://':" . PHP_EOL;
$conteudoAPI = file_get_contents('https://swapi.dev/api/planets/1/');
print_r(json_decode($conteudoAPI));