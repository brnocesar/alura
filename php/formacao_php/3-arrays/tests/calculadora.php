<pre>
<?php

require_once 'Calculadora.php';

$vetor = [4, 3, 5, 4, 5, 9, 7, 5, 2, 6];
// $vetor = [];

$calculadora = new Calculadora();
$resultado = $calculadora->calculaMedia($vetor);

if ( $resultado ) {
    echo "Resultado: $resultado.";
}
else {
    echo "Não foi possível calcular.";
}


?>
</pre>