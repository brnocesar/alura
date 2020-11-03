<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Factory\EspecialidadeFactory;
use App\Helper\UrlDataExtractor;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

class EspecialidadeController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager, 
        EspecialidadeRepository $repository, 
        EspecialidadeFactory $factory, 
        UrlDataExtractor $extractor, 
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($repository, $entityManager, $factory, $extractor, $cache, $logger);
    }
    
    /**
     * @param Especialidade $currentEntity
     * @param Especialidade $newEntity
     */
    public function updateCurrentEntity($currentEntity, $newEntity)
    {
        $currentEntity->setDescricao($newEntity->getDescricao());
    }

    /**
     * @param string $item_id
     */
    public function cacheItemId(string $item_id): string
    {
        return "especialidade_{$item_id}";
    }
}
