<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();

$repositorioDeAlunos = $entityManager->getRepository(Aluno::class);


if ( isset($argv[1]) ) {

    if ( is_numeric($argv[1]) ) {

        if ( $alunoEspecifico = $repositorioDeAlunos->find($argv[1]) ) {
            echo "{$alunoEspecifico->getId()}. {$alunoEspecifico->getNome()}\n";
            return;
        }
        else {
            echo "Aluno não encontrado pelo ID\n";
        }
    }
    else {

        $alunosEspecificos = $repositorioDeAlunos->findBy([
            'nome' => $argv[1]
        ]);

        if ( $alunosEspecificos ) {

            foreach ($alunosEspecificos as $aluno) {
                echo "{$aluno->getId()}. {$aluno->getNome()}\n";
            }
            return;
        }
        else {
            echo "Nenhum aluno encontrado pelo Nome\n";
        }
    }    
}

/**
 * @var Aluno[] $listaDeAlunos
*/
$listaDeAlunos = $repositorioDeAlunos->findAll();

foreach ($listaDeAlunos as $aluno) {
    echo "{$aluno->getId()}. {$aluno->getNome()}\n";
}