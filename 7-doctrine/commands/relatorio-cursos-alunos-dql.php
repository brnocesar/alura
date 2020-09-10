<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Helper\EntityManagerFactory;
use Doctrine\DBAL\Logging\DebugStack;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Helper/StandartOutputHelper.php';

$entityManager = (new EntityManagerFactory())->getEntityManager();
$repositorioDeAlunos = $entityManager->getRepository(Aluno::class);

$debugStack = new DebugStack();
$entityManager->getConfiguration()->setSQLLogger($debugStack);


$classeAluno = Aluno::class;
$dql = "SELECT a, t, c FROM $classeAluno a JOIN a.telefones t JOIN a.cursos c";

/** @var Aluno[] $alunos */
$alunos = $entityManager->createQuery($dql)->getResult();

foreach ( $alunos as $aluno ) {
    printAluno($aluno);
}

echo "\nConsultas realizadas no Banco: 1\n";
foreach ( $debugStack->queries as $queryInfo ) {
    echo "|> {$queryInfo['sql']} <|\n";
}
