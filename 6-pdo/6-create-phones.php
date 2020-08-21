<?php

$dbPath = __DIR__ . '/database/db.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);

$createTableSql = '
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT
    );

    CREATE TABLE IF NOT EXISTS phones (
        id INTEGER PRIMARY KEY,
        area_code TEXT,
        number TEXT,
        student_id INTEGER,
        FOREIGN KEY(student_id) REFERENCES students(id)
    );
';

$pdo->exec($createTableSql);

echo "=> Tables created.\n";


$insertPhonesSql = "
    INSERT INTO phones (
        area_code, number, student_id
    ) VALUES
        ('41', '123456789', 1), 
        ('41', '234567891', 7), 
        ('41', '345678912', 8),
        ('41', '345678910', 8);
";

$pdo->exec($insertPhonesSql);

echo "=> Phones created.\n";