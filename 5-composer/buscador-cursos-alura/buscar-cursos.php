#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use Alura\BuscadorDeFormacoes\Buscador;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client(['base_uri' => 'https://www.alura.com.br/']);
$crawler = new Crawler();

$buscador = new Buscador($client, $crawler);
$formacoes = $buscador->buscar('/formacoes');

foreach ( $formacoes as $formacao ) {
    exibeMensagem($formacao);
}

Teste::metodo();

?>