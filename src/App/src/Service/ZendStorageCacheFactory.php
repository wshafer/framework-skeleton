<?php

namespace App\Service;

use Psr\Container\ContainerInterface;

class ZendStorageCacheFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ZendStorageCache($container->get('Cache\Doctrine'));
    }
}
