<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Factory\EspecialidadeFactory;
use App\Helper\UrlDataExtractor;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EspecialidadeController extends BaseController
{
    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $repository, EspecialidadeFactory $factory, UrlDataExtractor $extractor)
    {
        parent::__construct($repository, $entityManager, $factory, $extractor);
    }
    
    /**
     * @param Especialidade $currentEntity
     * @param Especialidade $newEntity
     */
    public function updateCurrentEntity($currentEntity, $newEntity)
    {
        $currentEntity->setDescricao($newEntity->getDescricao());
    }
}
