<pre>
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

include "autoload.php";

use Validacao;
use ContaCorrente;
use excecao\SaldoInsuficienteExcecao;

$contaMaria = new ContaCorrente("Maria" , "5678", "475869", 800.00);
echo "Conta n: " . ContaCorrente::$totalContas . " - taxa: " . ContaCorrente::$taxaOperacao;

$contaJoao = new ContaCorrente("Joao", "1234", "142536", 500.00);
echo "\nConta n: " . ContaCorrente::$totalContas . " - taxa: " . ContaCorrente::$taxaOperacao;

$contaJosias = new ContaCorrente("Jose", "9874", "986532", 650.00);
echo "\nConta n: " . ContaCorrente::$totalContas . " - taxa: " . ContaCorrente::$taxaOperacao;

$contaJocelia = new ContaCorrente("Jose", "9874", "986532", 650.00);
echo "\nConta n: " . ContaCorrente::$totalContas . " - taxa: " . ContaCorrente::$taxaOperacao;

$contaJojo = new ContaCorrente("Jose", "9874", "986532", 650.00);
echo "\nConta n: " . ContaCorrente::$totalContas . " - taxa: " . ContaCorrente::$taxaOperacao;

$contaJofrey = new ContaCorrente("Jose", "9874", "986532", 650.00);
echo "\nConta n: " . ContaCorrente::$totalContas . " - taxa: " . ContaCorrente::$taxaOperacao;


echo "\n\n - - - - - - - # - - - - - - -\n\n";

$valor = -100;
$origem = 'contaMaria';
$destino = 'contaJoao';
echo $$origem->getTitular() . " tranfere $valor para " . $$destino->getTitular();
echo "\nSaldo inicial:\t" . $$origem->getTitular() . " = " . $$origem->getSaldo();
echo "\t" . $$destino->getTitular() . " = " . $$destino->getSaldo();
try {
    $$origem->transferir($valor, $$destino);
} catch ( InvalidArgumentException $deuRuim) {
    echo "\nArgumento inválido: " . $deuRuim->getMessage();
} catch ( SaldoInsuficienteExcecao $deuRuim) {
    $$origem->totalSaquesNaoPermitidos++;
    echo "\n<b>Exceção criada:</b> " . $deuRuim->getMessage() . "\tSaldo: " . $deuRuim->saldo . "\tValor do saque:" . $deuRuim->valor;
} catch ( Exception $deuRuim ) {
    echo "\nExceção: " . $deuRuim->getMessage();
    // var_dump($deuRuim->getPrevious()->getTraceAsString());
}
echo "\nSaldo final:\t" . $$origem->getTitular() . " = " . $$origem->getSaldo();
echo "\t" . $$destino->getTitular() . " = " . $$destino->getSaldo();


echo "\n\n - - - - - - - # - - - - - - -\n\n";

try {
    $contaJoao['atributo'];
} catch (Error $shitHappens ) {
    echo "Erro: " . $shitHappens->getMessage();
}


echo "\n\n - - - - - - - # - - - - - - -\n\n";

echo "Operações não realizadas: " . ContaCorrente::$totalOperacoesNaoRealizadas;

?>
</pre>