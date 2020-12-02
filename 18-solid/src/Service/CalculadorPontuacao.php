<?php

namespace Learning\Solid\Service;

use Learning\Solid\Model\AluraMais;
use Learning\Solid\Model\Curso;

class CalculadorPontuacao
{
    public function recuperarPontuacao($conteudo)
    {
        if ($conteudo instanceof Curso) {
            return 100;
        } else if ($conteudo instanceof AluraMais) {
            return $conteudo->minutosDeDuracao() * 2;
        } else {
            throw new \DomainException('Apenas Cursos e videos Alura+ possuem pontuações');
        }
    }
}
