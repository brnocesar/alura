<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    public static function createConnection(): PDO
    {
        $dbPath = __DIR__ . '/../../../database/db.sqlite';
        return new PDO('sqlite:' . $dbPath);
    }
}
