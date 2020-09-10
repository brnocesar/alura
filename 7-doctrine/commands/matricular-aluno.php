<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Entity\Curso;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';
$instrucoes = "Você deve digitar os ID's de um Aluno e um Curso (nesta ordem).\n";

if ( !isset($argv[1]) or !isset($argv[2]) ) {
    echo $instrucoes;
    return;
}

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();


/** @var Aluno $aluno */
$aluno = $entityManager->find(Aluno::class, $argv[1]);

/** @var Curso $curso */
$curso = $entityManager->find(Curso::class, $argv[2]);

if ( is_null($aluno) or !$curso ) {
    echo "Aluno ou Curso não encontrados.\n$instrucoes";
    return;
}

$aluno->addCurso($curso);
// $curso->addAluno($aluno); // tanto faz qual dos dois é chamado

$entityManager->flush();
echo "Aluno matriculado.\n";
return;