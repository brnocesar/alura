<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Entity\Telefone;
use Alura\Doctrine\Helper\EntityManagerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$entityManager = (new EntityManagerFactory())->getEntityManager();

$aluno = new Aluno($argv[1]);                   // cria instância da entidade Aluno (1)
$entityManager->persist($aluno);                // GE monitora a instância criada (2)

for ($i=2; $i < $argc; $i++) {

    $telefone = new Telefone();                 // (4)
    $telefone->setNumero($argv[$i]);
    // $entityManager->persist($telefone);      // (5) 

    $aluno->addTelefone($telefone);             // (6)
}

$entityManager->flush();                        // efetiva a persistência no Banco (3)

// os passos 1-3 são descritos na quarto tópico "4 Persistindo registros no Banco"
// os passos 4-6 são descritos na sétimo tópico "7 Atualizando o CRUD de Alunos"