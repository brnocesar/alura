<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Entity\Telefone;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();

$repositorioDeAlunos = $entityManager->getRepository(Aluno::class);


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

        if ( $aluno = $repositorioDeAlunos->find($argv[1]) ) {
            printAluno($aluno);
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
                printAluno($aluno);
            }
            return;
        }
        else {
            echo "Nenhum aluno encontrado pelo Nome\n";
        }
    }    
}

$query = $entityManager->createQuery('SELECT aluno FROM Alura\\Doctrine\\Entity\\Aluno aluno');
$listaDeAlunos = $query->getResult();

foreach ($listaDeAlunos as $aluno) {
    printAluno($aluno);
}
