<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();


if ( isset($argv[1]) ) {

    if ( $aluno = $entityManager->getReference(Aluno::class, $argv[1]) ) {
    /* if ( $aluno = $entityManager->find(Aluno::class, $argv[1]) ) { */
        
        $entityManager->remove($aluno);
        $entityManager->flush();
        echo "Aluno removido.\n";
        return;
    }
    echo "Impossível remover Aluno, não foi encontrado.\n";
    return;
}

echo "Impossível remover Aluno. Parâmetro ID não foi passado.\n";