<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManager = (new EntityManagerFactory())->getEntityManager();
$classe = Aluno::class;
$dql = "SELECT COUNT(a) FROM $classe a";

echo "Total de alunos: {$entityManager->createQuery($dql)->getSingleScalarResult()}\n";