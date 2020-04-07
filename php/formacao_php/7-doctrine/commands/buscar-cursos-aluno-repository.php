<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Entity\Telefone;
use Alura\Doctrine\Helper\EntityManagerFactory;
use Doctrine\DBAL\Logging\DebugStack;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();

$repositorioDeAlunos = $entityManager->getRepository(Aluno::class);


/** @var Aluno[] $alunos */
$alunos = $repositorioDeAlunos->buscarCursosPorAluno(true, true);

foreach ( $alunos as $aluno ) {
    
    $telefones = $aluno->getTelefones()->map(function(Telefone $telefone){
        return $telefone->getNumero();
    })->toArray();

    echo "{$aluno->getId()} \tAluno(a): \t{$aluno->getNome()}" . PHP_EOL;
    echo "\tTelefone(s): \t" . implode(', ', $telefones) . PHP_EOL;
    
    echo "\tCurso(s): \t";
    foreach ( $cursos = $aluno->getCursos() as $curso ) {
        echo "{$curso->getNome()}; ";
    }
    echo PHP_EOL . PHP_EOL;
}
