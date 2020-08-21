<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$student = new Student(
    null,
    'Harry James Potter',
    new \DateTimeImmutable('1980-07-31')
);

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES ('{$student->name()}', '{$student->birthDate()->format('Y-m-d')}');";

echo "\n=> script SQL\n\t$sqlInsert\n\n";

var_dump($pdo->exec($sqlInsert));


// 5) Quando usamos inputs do usuário devemos prevenir a ocorrência de SQL injection
$nome = "'Harry', ''); DROP TABLE students; -- James Potter',"; // instrução com SQL injection
$sqlInsert = "INSERT INTO students (name, birth_date) VALUES ('{$nome}', '1980-07-31');";
echo "\n=> script com SQL injection\n\t$sqlInsert\n";

// devemos tratar a instrução e passar os parâmetros para depois executar
// $sqlInsert = "INSERT INTO students (name, birth_date) VALUES (?, ?);"; // sem nomear os parametros
// $statement->bindValue(1, $nome); // parametros anonimos, usamos a posicao
// $statement->bindValue(2, '1980-07-31');

$nome = "Ronald Weasley"; // nome sem SQL injection
$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date);"; // nomeando os parametros
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(':name', $nome); // parametros nomeados
$statement->bindValue('birth_date', '1980-03-01');

if ($statement->execute()) {
    echo "\n=> Aluno incluido\n";
}

// não é possível misturar os dois padrões: parâmetros nomeados e anônimos
// não é necessário colocar os ':' ao indicar qual parâmetro está recebendo o valor
