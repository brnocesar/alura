<?php 

namespace Alura\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;

class AlunoRepository extends EntityRepository
{
    public function buscarCursosPorAluno(bool $cursos, bool $telefones)
    {
        $builder = $this->createQueryBuilder('a');

        if ( $cursos ) {
            $builder->join('a.cursos', 'c')->addSelect('c');
        }

        if ( $telefones ) {
            $builder->join('a.telefones', 't')->addSelect('t');
        }

        $query = $builder->getQuery();

        return $query->getResult();
    }
}