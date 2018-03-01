<?php

namespace Cache\Service;

use Cache\Bridge\Doctrine\DoctrineCacheBridge;
use Psr\Container\ContainerInterface;

class DoctrineCacheFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new DoctrineCacheBridge($container->get('Cache\Doctrine'));
    }
}
