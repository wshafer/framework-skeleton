<?php
declare(strict_types=1);

namespace Auth\Config;

use Psr\Container\ContainerInterface;

class AuthConfigFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new AuthConfig($config);
    }
}
