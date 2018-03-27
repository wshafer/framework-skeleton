<?php

declare(strict_types=1);

namespace OAuth\Command;

use Interop\Container\ContainerInterface;
use OAuth\Config\Config;

class KeyGeneratorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(Config::class);
        return new KeyGenerator($config);
    }
}
