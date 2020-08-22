<?php

use Symfony\Component\HttpClient\HttpClient;

require_once 'vendor/autoload.php';

$client = HttpClient::create();

$request = $client->request('GET', 'https://brnocesar.github.io/');

$status = $request->getStatusCode();
var_dump($status);

$content = $request->getContent();
// var_dump($content);
