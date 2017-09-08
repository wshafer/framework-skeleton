<?php
declare(strict_types=1);

namespace Auth\Command;

use Psr\Container\ContainerInterface;

class AclNewPrivilegeCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $entityManager =  $container->get('doctrine.entity_manager.orm_default');

        return new AclNewPrivilegeCommand($entityManager);
    }
}
