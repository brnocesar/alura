<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

require "Validacao.php";
require "ContaCorrente.php";

$contaMaria = new ContaCorrente("Maria" , "5678", "475869", 800.00);
$contaJoao = new ContaCorrente("Joao", "1234", "142536", 500.00);

echo "<br><br> ".$contaMaria->getTitular().":<br>";
var_dump($contaMaria);

echo "<br><br> ".$contaJoao->getTitular().":<br>";
var_dump($contaJoao);

$contaJoao->sacar(400.00)->depositar(300.00);
$contaJoao->numero = "111111";
// $contaJoao->saldo = 0;

echo "<br><br> ".$contaJoao->getTitular().":<br>";
var_dump($contaJoao);

echo "<br><br>- ".$contaJoao->getTitular().": ";
echo "<br>--- ".$contaJoao->getSaldo();

echo "<br><br># ".$contaJoao->getTitular().": ".$contaJoao->getSaldo();
echo "<br># ".$contaMaria->getTitular().": ".$contaMaria->getSaldo();
echo "<br># Tranferência da conta de ".$contaJoao->getTitular()." para a conta de ".$contaMaria->getTitular().".";
$contaJoao->transferir(50.00, $contaMaria);
echo "<br># ".$contaJoao->getTitular().": ".$contaJoao->getSaldo();
echo "<br># ".$contaMaria->getTitular().": ".$contaMaria->getSaldo();

echo $contaMaria;

Validacao::hello();
