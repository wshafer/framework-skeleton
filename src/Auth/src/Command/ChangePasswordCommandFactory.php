<?php
declare(strict_types=1);

namespace Auth\Command;

use Auth\Config\AuthConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class ChangePasswordCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager =  $container->get('doctrine.entity_manager.orm_default');

        /** @var AuthConfig $authConfig */
        $authConfig = $container->get(AuthConfig::class);

        return new ChangePasswordCommand($entityManager, $authConfig);
    }
}
