<?php

use Alura\Doctrine\Entity\Aluno;
use Alura\Doctrine\Entity\Telefone;

function printAluno(Aluno $aluno)
{
    $telefones = $aluno->getTelefones()->map(fn (Telefone $telefone) => $telefone->getNumero())->toArray();

    echo "{$aluno->getId()} \tAluno(a): {$aluno->getNome()}\n";
    echo "\tTelefone(s): " . implode(', ', $telefones) . PHP_EOL;

    echo "\tCurso(s): ";
    foreach ( $aluno->getCursos() as $curso ) {
        echo "{$curso->getNome()}; ";
    }
    echo PHP_EOL;
}
