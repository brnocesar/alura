<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Entity\Telefone;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->getEntityManager();


$aluno = new Aluno();                                           // passo 1
$aluno->setNome($argv[1]);
$entityManager->persist($aluno);                                // passo 2

for ($i=2; $i < $argc; $i++) {

    $telefone = new Telefone();                                 // passo 4
    $telefone->setNumero($argv[$i]);
    // $entityManager->persist($telefone);                         // passo 5 

    $aluno->addTelefone($telefone);                             // passo 6
}

$entityManager->flush();                                        // passo 3