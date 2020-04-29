<pre>
<?php

use Alura\ArrayUtils;

require_once 'src/ArrayUtils.php';

$correntistas = [
    "Giovanni",
    "Jõao",
    "Maria",
    "Luis",
    "Luiza",
];

$correntistasNaoPagantes = [
    "Giovanni",
    "Maria",
    "Luiza",
];

$correntistasPagantes = array_diff($correntistas, $correntistasNaoPagantes);

var_dump($correntistasPagantes);

$saldos = [
    2500,
    3000,
    4400,
    1000,
    8700
];

$relacionados = array_combine($correntistas, $saldos);

$relacionados['Mateus'] = 3333;

var_dump($relacionados);

var_dump($relacionados['Giovanni']);

var_dump( ArrayUtils::saldoMaiorQue(3000, $relacionados) );

?>
</pre>