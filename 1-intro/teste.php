<?php

require 'funcoes.php';
require 'contas.php';

$contasCorrentes[142536] = sacar($contasCorrentes[142536], 500);

foreach ( $contasCorrentes as $cpf => $conta) {
    exibeMensagem("$cpf {$conta['titular']} {$conta['saldo']}");
}

$contasCorrentes[142536] = depositar($contasCorrentes[142536], 1);

titularMaiusculo( $contasCorrentes[142536] );

// unset( $contasCorrentes[123456] );

foreach ( $contasCorrentes as $cpf => $conta) {
    // list( 'titular' => $titular, 'saldo' => $saldo ) = $conta;
    [ 'titular' => $titular, 'saldo' => $saldo ] = $conta;
    exibeMensagem("$cpf $titular $saldo");
}
echo "<ul>";
foreach ( $contasCorrentes as $cpf => $conta) {
    exibeConta( $conta );
}
echo "</ul>";
?>

<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport">
    <title>Documento</title>
</head>
<body>
    <h1>Contas correntes</h1>

    <dl>
        <?php foreach( $contasCorrentes as $cpf => $conta ) { ?>
        <dt>
            <h3> <?php echo $conta['titular']; ?> - <?= $cpf; ?></h3>
        </dt>
        <dd> Saldo: <?php echo $conta['saldo'];?> </dd>
        <?php } ?>
    </dl>
</body>
</html>
