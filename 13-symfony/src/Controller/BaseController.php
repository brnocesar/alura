<?php

namespace App\Controller;

use App\Factory\EntityFactory;
use App\Factory\ResponseFactory;
use App\Helper\UrlDataExtractor;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected $repository;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var EntityFactory
     */
    protected $factory;
    /**
     * @var UrlDataExtractor
     */
    protected $extractor;
    /**
     * @var CacheItemPoolInterface
     */
    protected $cache;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    public function __construct(
        ObjectRepository $repository, 
        EntityManagerInterface $entityManager, 
        EntityFactory $factory, 
        UrlDataExtractor $extractor, 
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        $this->repository    = $repository;
        $this->entityManager = $entityManager;
        $this->factory       = $factory;
        $this->extractor     = $extractor;
        $this->cache         = $cache;
        $this->logger        = $logger;
    }

    
    public function index(Request $request): Response
    {
        $currentPage  = $this->extractor->getCurrentPage($request);
        $itensPerPage = $this->extractor->getItensPerPage($request); 

        $entityList = $this->repository->findBy(
            $this->extractor->getFilterParams($request), 
            $this->extractor->getSortParams($request), 
            $itensPerPage, 
            $this->extractor->getOffsetSearch($request)
        );

        $totalItens = count($this->repository->findAll()); // trocar isso

        $response = new ResponseFactory($entityList, Response::HTTP_OK, $currentPage, $itensPerPage, $totalItens);

        return $response->getResponse();
    }

    public function store(Request $request): Response
    {
        $entity = $this->factory->createEntity($request->getContent());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $this->createOrUpdateCache($entity);
        $this->logger->notice(
            'Novo registro de {entidade} adicionado com id = {id}.', 
            [
                'entidade' => get_class($entity),
                'id'       => $entity->getId()
            ]
        );

        return new JsonResponse($entity, Response::HTTP_CREATED);
    }
    
    public function show(int $id): Response
    {
        $entity = $this->cache->hasItem($this->cacheItemId($id)) 
            ? $this->cache->getItem($this->cacheItemId($id))->get() 
            : $this->repository->find($id);
        // $entity = $this->cache->getItem($this->cacheItemId($id))->get() ?? $this->repository->find($id);
        
        $codigoRetorno = is_null($entity) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK;
        
        return new JsonResponse($entity, $codigoRetorno);
    }

    public function update(int $id, Request $request): Response
    {
        $entity = $this->repository->find($id);
        if ( is_null($entity) ) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        
        $newEntity = $this->factory->createEntity($request->getContent());
        
        $this->updateCurrentEntity($entity, $newEntity);
        $this->entityManager->flush();

        $this->createOrUpdateCache($entity);

        return new JsonResponse($entity, Response::HTTP_OK);
    }

    public function destroy(int $id): Response
    {
        $entity = $this->repository->find($id);
        if ( is_null($entity) ) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        $this->cache->deleteItem($this->cacheItemId($id));

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    private function createOrUpdateCache($entity): void
    {
        $cacheItem = $this->cache->getItem($this->cacheItemId($entity->getId()));
        $cacheItem->set($entity);
        $this->cache->save($cacheItem);
    }

    abstract public function updateCurrentEntity($currentEntity, $newEntity);

    abstract public function cacheItemId(string $item_id): string;
}
