<?php

namespace Alura\Doctrine\Helper;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory
{
    /**
     * @return EntityManagerInterface
    */
    public function getEntityManager(): EntityManagerInterface
    {
        $rootDir = __DIR__ . '/../..';
        $config = Setup::createAnnotationMetadataConfiguration(
            [$rootDir . '/src'],
            true /* dev env */
        );

        $connection = [
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'alura_doctrine',
            'user'      => 'dev',
            'password'  => '1234',
        ];

        return EntityManager::create($connection, $config);
    }
}