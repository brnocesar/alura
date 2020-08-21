<?php

namespace Alura\Pdo\Domain\Repository;

use Alura\Pdo\Domain\Model\Student;
use DateTimeInterface;
use PDO;
use PDOStatement;

interface StudentRepository
{
    public function allStudents(): array;
    public function studentsWithPhone(): array;
    public function studentsBirthAt(DateTimeInterface $birthDate): array;
    // public function hydrateStudentList(PDO $stmt): array;
    public function hydrateStudentList(PDOStatement $stmt): array;
    public function save(Student $student): bool;
    public function remove(Student $student): bool;
}
