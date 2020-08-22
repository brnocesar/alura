<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    public static function createConnection(): PDO
    {
        $dbPath = __DIR__ . '/../../../database/db.sqlite'; // sqlite
        $connection = new PDO('sqlite:' . $dbPath); // sqlite
        // $connection = new PDO('mysql:localhost;dbname=banco', 'bruno', '1234'); // mysql
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }
}
