<?php

namespace Database\Repository;

use Database\Event\FetchRepositoryEvent;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Repository\RepositoryFactory as RepositoryFactoryInterface;
use Doctrine\ORM\Repository\RepositoryFactory;

class RepositoryFactoryDecorator implements RepositoryFactoryInterface
{
    protected $originalRepositoryFactory;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->originalRepositoryFactory = $repositoryFactory;
    }

    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        /** @var EntityRepository $repository */
        $repository = $this->originalRepositoryFactory->getRepository(
            $entityManager,
            $entityName
        );

        $eventManager = $entityManager->getEventManager();

        $event = new FetchRepositoryEvent();
        $event->setRepository($repository);

        $eventManager->dispatchEvent(FetchRepositoryEvent::EVENT_NAME, $event);

        return $repository;
    }
}
