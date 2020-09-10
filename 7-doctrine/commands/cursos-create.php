<?php

use Alura\Doctrine\Entity\Curso;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManager = (new EntityManagerFactory())->getEntityManager();

if ( isset($argv[1]) ) {
    
    $curso = new Curso();
    $curso->setNome($argv[1]);
    $entityManager->persist($curso);
    
    $entityManager->flush();
}