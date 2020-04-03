<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Entity\Curso;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();


/** @var Aluno $aluno */
$aluno = $entityManager->find(Aluno::class, $argv[1]);

/** @var Curso $curso */
$curso = $entityManager->find(Curso::class, $argv[2]);

if ( !$aluno or !$curso ) {
    echo "Aluno ou Curso não encontrados.\nDigite os ID's de um Aluno e um Curso (nesta ordem).\n";
    return;
}

$aluno->addCurso($curso);
$curso->addAluno($aluno);

$entityManager->flush();
echo "Aluno matriculado.\n";
return;