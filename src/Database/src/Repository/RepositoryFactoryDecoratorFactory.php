<?php

namespace Database\Repository;

use Doctrine\ORM\Repository\DefaultRepositoryFactory;
use Psr\Container\ContainerInterface;

class RepositoryFactoryDecoratorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $repositoryFactory = new DefaultRepositoryFactory();
        return new RepositoryFactoryDecorator($repositoryFactory);
    }
}
