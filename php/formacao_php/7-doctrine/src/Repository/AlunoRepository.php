<?php 

namespace Alura\Doctrine\Repository;

use Alura\Doctrine\Entity\Aluno;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

class AlunoRepository extends EntityRepository
{
    public function buscarCursosPorAluno()
    {
        $query = $this->createQueryBuilder('a')
            ->join('a.telefones', 't')
            ->join('a.cursos', 'c')
            ->addSelect('t')
            ->addSelect('c')
            ->getQuery();

        return $query->getResult();
    }
}