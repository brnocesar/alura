<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$dbPath = __DIR__ . '/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);


// 1) Recurando todos os registros
$statement = $pdo->query('SELECT * FROM students');

// var_dump($statement->fetchAll());

$studentDataList = $statement->fetchAll(PDO::FETCH_ASSOC);

// var_dump($studentDataList);

$studentList = [];

foreach ($studentDataList as $studentData) {
    $studentList[] = new Student(
        $studentData['id'],
        $studentData['name'],
        new DateTimeImmutable($studentData['birth_date'])
    );
}

var_dump($studentList);

// 2) Recuperando um unico registro do Banco
$statement = $pdo->query('SELECT * FROM students WHERE id = 1');
$studentData = $statement->fetch(PDO::FETCH_ASSOC);

var_dump($studentData);


// 3) Se for necessário recuperar "vários" registros do Banco, o ideal é fazer um por um
// e conforme eles forem sendo recuperados, devem ser usados
// quando não houver mais linhas para serem recuperadas, o fetch ira retornar FALSE
$statement = $pdo->query('SELECT * FROM students WHERE id = 1');

while ($studentData = $statement->fetch(PDO::FETCH_ASSOC)) {
    $student = new Student(
        $studentData['id'],
        $studentData['name'],
        new DateTimeImmutable($studentData['birth_date'])
    );

    echo "{$student->age()}\n";
}


// 4) Recuperando apenas uma coluna, basta passar o indice da coluna, sendo zero a primeira
// pode ser colocado em um laço também, a cada chamada o "ponteiro/cursor" é posicionado na próxima linha
$statement = $pdo->query('SELECT * FROM students WHERE id = 1');
var_dump($statement->fetchColumn(1));
