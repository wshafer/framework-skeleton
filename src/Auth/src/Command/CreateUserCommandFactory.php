<?php
declare(strict_types=1);

namespace Auth\Command;

use Auth\Config\AuthConfig;
use Psr\Container\ContainerInterface;

class CreateUserCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $entityManager =  $container->get('doctrine.entity_manager.orm_default');

        /** @var AuthConfig $authConfig */
        $authConfig = $container->get(AuthConfig::class);

        return new CreateUserCommand($entityManager, $authConfig);
    }
}
