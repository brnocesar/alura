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


foreach ( $repositorioDeAlunos->findAll() as $aluno ) {
    printAluno($aluno);
}

// echo "\nConsultas realizadas no Banco:\n";
// foreach ( $debugStack->queries as $queryInfo ) {
//     echo "=> {$queryInfo['sql']}\n";
// }
