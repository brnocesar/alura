<?php

use Alura\Doctrine\Helper\EntityManagerFactory;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// replace with file to your own project bootstrap
// require_once 'bootstrap.php';
require_once __DIR__ . '/vendor/autoload.php';

// replace with mechanism to retrieve EntityManager in your app
// $entityManager = GetEntityManager();
$entityManagerFactory = new EntityManagerFactory();
$entityManager = $entityManagerFactory->GetEntityManager();


return ConsoleRunner::createHelperSet($entityManager);

/* agora podemos rodar o comando: $ php vendor/bin/doctrine */