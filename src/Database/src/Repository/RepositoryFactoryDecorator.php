<?php

namespace Database\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory as RepositoryFactoryInterface;
use Psr\Container\ContainerInterface;

class RepositoryFactoryDecorator implements RepositoryFactoryInterface
{
    protected $originalRepositoryFactory;

    protected $container;

    protected $config;

    public function __construct(
        RepositoryFactoryInterface $realRepositoryFactory,
        ContainerInterface $container,
        array $config
    ) {
        $this->originalRepositoryFactory = $realRepositoryFactory;
        $this->container = $container;
        $this->config = $config;
    }

    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $repository = $this->originalRepositoryFactory->getRepository(
            $entityManager,
            $entityName
        );

        if ($repository instanceof ConfigAwareInterface) {
            $repository->setConfig($this->config);
        }

        if ($repository instanceof ContainerAwareInterface) {
            $repository->setContainer($this->container);
        }

        return $repository;
    }
}
