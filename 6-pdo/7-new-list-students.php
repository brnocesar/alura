<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();
$repository = new PdoStudentRepository($pdo);

// $studentList = $repository->allStudents();
$studentList = $repository->studentsWithPhone();

var_dump($studentList);

echo "\n{$studentList[1]->phones()[0]->formattedPhone()}\n";