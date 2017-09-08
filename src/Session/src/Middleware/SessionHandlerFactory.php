<?php
declare(strict_types=1);

namespace Session\Middleware;

use Psr\Container\ContainerInterface;
use Zend\Session\ManagerInterface;

class SessionHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new SessionHandler($container->get(ManagerInterface::class));
    }
}
