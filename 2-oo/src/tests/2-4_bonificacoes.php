<pre>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Alura\Banco\Modelo\{Cpf, Endereco, Pessoa};
use Alura\Banco\Modelo\Conta\{Conta, ContaCorrente, ContaPoupanca, Titular};
use Alura\Banco\Modelo\Funcionario\{Funcionario, Diretor, Gerente, Desenvolvedor, EditorVideo};
use Alura\Banco\Servico\ControladorBonificacoes;

require_once 'autoload.php';


$desenvolvedor = new Desenvolvedor(
    'Bruno', 
    new Cpf('123.456.789-01'), 
    2000
);
$gerente = new Gerente(
    'Ana Luiza', 
    new Cpf('123.456.789-02'), 
    3000
);
$diretor = new Diretor(
    'Ana Paula', 
    new Cpf('123.456.789-03'),  
    5000
);
$editor = new EditorVideo(
    'Jõao Gabriel', 
    new Cpf('123.456.789-04'),  
    2500
);

$controlador = new ControladorBonificacoes();
$controlador->adicionaBonificacao($desenvolvedor);
$controlador->adicionaBonificacao($gerente);
$controlador->adicionaBonificacao($diretor);
$controlador->adicionaBonificacao($editor);
echo $controlador->getTotal();

?>
</pre>