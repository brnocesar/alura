<pre>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Alura\Banco\Modelo\{Cpf, Endereco, Pessoa};
use Alura\Banco\Modelo\Conta\{Conta, ContaCorrente, ContaPoupanca, Titular};
use Alura\Banco\Modelo\Funcionario\{Funcionario, Diretor, Gerente, Desenvolvedor, EditorVideo};
use Alura\Banco\Servico\{Autenticador, ControladorBonificacoes};

require_once 'autoload.php';


$autenticador = new Autenticador();

$diretor = new Diretor(
    'Ana Paula', 
    new Cpf('123.456.789-03'),  
    5000
);
$autenticador->tentaLogin( $diretor, '12345');
echo "\n== # ==\n";

$gerente = new Gerente(
    'Ana Luiza', 
    new Cpf('123.456.789-02'), 
    3000
);
$autenticador->tentaLogin( $gerente, '4321');
echo "\n== # ==\n";

$titular = new Titular(
    'Ana Luiza', 
    new Cpf('123.456.789-02'), 
    new Endereco('Rua da Frente', '53B', 'Bairro dos Perdidos', 'Cidade Isolada')
);
$autenticador->tentaLogin( $titular, '4321');
echo "\n== # ==\n";

?>
</pre>