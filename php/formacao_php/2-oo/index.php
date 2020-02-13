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

$umEndereco = new Endereco('Rua da Frente', '53B', 'Bairro dos Perdidos', 'Cidade Isolada');
echo $umEndereco . PHP_EOL;
$doisEndereco = new Endereco('Rua de Tras', '35', 'Bairro dos Encontrados', 'Cidade Isolada');
echo $doisEndereco . PHP_EOL;
//isso é possível devido a implementação do método mágico __toString()

echo $umEndereco->rua . PHP_EOL;
echo $umEndereco->cidade . PHP_EOL;
//isso é possível devido a implementação do método mágico __get() na classe Endereco

$desenvolvedor = new Desenvolvedor(
    'Bruno', 
    new Cpf('123.456.789-01'), 
    2000
);
echo $desenvolvedor->nome . PHP_EOL;
//isso é possível devido a implementação do método mágico __get() na trait GetAtributos que eh adicionada na classe Pessoa


?>
</pre>