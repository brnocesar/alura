<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Helper/StandartOutputHelper.php';

$entityManager = (new EntityManagerFactory())->getEntityManager();

$classe  = Aluno::class;
$dql     = "SELECT aluno FROM $classe aluno";
$order   = " ORDER BY aluno.nome";
$dqlId   = $dql . " WHERE aluno.id=";
$dqlNome = $dql . " WHERE aluno.nome LIKE ";


if ( isset($argv[1]) ) {

    if ( is_numeric($argv[1]) ) {

        $query = $entityManager->createQuery($dqlId . $argv[1]);

        if ( !empty($query->getResult()) ) {

            printAluno( $query->getResult()[0] );
            return;
        }

        echo "Aluno não encontrado pelo ID\n";
        return;
    }

    $query = $entityManager->createQuery("{$dqlNome} '%{$argv[1]}%' {$order}");
    $alunos = $query->getResult();

    if ( !empty($alunos) ) {

        foreach ( $alunos as $aluno ) {
            printAluno($aluno);
        }
        return;
    }
    echo "Nenhum aluno encontrado pelo Nome\n\n";
}


$query = $entityManager->createQuery($dql . $order);

foreach ( $query->getResult() as $aluno ) {
    printAluno($aluno);
}
