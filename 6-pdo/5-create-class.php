<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();
$studentRepository = new PdoStudentRepository($connection);


$connection->beginTransaction();

try {
    $student = new Student(
        null, 
        'Hermione Jean Granger', 
        new DateTimeImmutable('1979-10-19')
    );
    
    $studentRepository->save($student);
    
    $studentRepository->save(new Student(
        null, 
        'Hermione Jean Granger 2', 
        new DateTimeImmutable('1979-10-19')
    ));

    $connection->commit();

} catch (PDOException $e) {
    echo $e->getMessage();

    $connection->rollBack();
}

echo "\n";