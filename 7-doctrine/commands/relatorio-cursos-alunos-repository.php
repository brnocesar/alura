<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Helper\EntityManagerFactory;
use Alura\Doctrine\Repository\AlunoRepository;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Helper/StandartOutputHelper.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();

/** @var AlunoRepository */
$repositorioDeAlunos = $entityManager->getRepository(Aluno::class);


/** @var Aluno[] $alunos */
$alunos = $repositorioDeAlunos->buscarCursosPorAluno(true, true);

foreach ( $alunos as $aluno ) {
    printAluno($aluno);
}
