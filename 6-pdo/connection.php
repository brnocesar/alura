<?php 

$dbPath = __DIR__ . '/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

echo "=> Conexão realizada com Banco de dados!\n";

$pdo->exec('CREATE TABLE students (id INTEGER PRIMARY KEY, name TEXT, birth_date TEXT);');

echo "=> Tabela 'students' criada com sucesso!\n";