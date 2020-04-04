<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Entity\Telefone;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();

$dql = "SELECT aluno FROM Alura\\Doctrine\\Entity\\Aluno aluno";
$order = " ORDER BY aluno.nome";
$dqlId = $dql . " WHERE aluno.id=";
$dqlNome = $dql . " WHERE aluno.nome LIKE ";


function printAluno(Aluno $aluno)
{
    $telefones = $aluno->getTelefones()->map(function(Telefone $telefone){
        return $telefone->getNumero();
    })->toArray();

    echo "{$aluno->getId()} \tAluno(a): \t{$aluno->getNome()}" . PHP_EOL;
    echo "\tTelefone(s): \t" . implode(', ', $telefones) . PHP_EOL . PHP_EOL;
}


if ( isset($argv[1]) ) {

    if ( is_numeric($argv[1]) ) {

        $query = $entityManager->createQuery($dqlId . $argv[1]);

        if ( !empty($query->getResult()) ) {

            printAluno( $query->getResult()[0] );
            return;
        }
        else {
            echo "Aluno não encontrado pelo ID\n";
        }
    }
    else {

        $query = $entityManager->createQuery("{$dqlNome} '%{$argv[1]}%' {$order}");
        $alunos = $query->getResult();

        if ( !empty($alunos) ) {

            foreach ( $alunos as $aluno ) {
                printAluno($aluno);
            }
            return;
        }
        else {
            echo "Nenhum aluno encontrado pelo Nome\n";
        }
    }    
}

$query = $entityManager->createQuery($dql . $order);

foreach ( $query->getResult() as $aluno ) {
    printAluno($aluno);
}
