<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();

// $repositorioDeAlunos = $entityManager->getRepository(Aluno::class);


if ( isset($argv[1]) and isset($argv[2]) ) {
    
    /**
     * @var Aluno $aluno
    */
    if ( $aluno = $entityManager->find(Aluno::class, $argv[1]) ) {
        
        // $aluno = $repositorioDeAlunos->find($id);
        $aluno->setNome($argv[2]);
        $entityManager->flush();
        echo "Aluno atualizado.\n";
        return;
    }
    echo "Impossível atualizar Aluno, não foi encontrado.\n";
    return;
}

echo "Impossível atualizar Aluno. Parâmetros faltando.\n";
