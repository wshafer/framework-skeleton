<?php

declare(strict_types=1);

namespace OAuth\Command\Scope;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use OAuth\Config\Config;
use OAuth\Entity\Scope;
use OAuth\Repository\ScopeRepository;

class CreateFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('Oauth\Doctrine\EntityManager');

        /** @var ScopeRepository $scopeRepo */
        $scopeRepo = $entityManager->getRepository(Scope::class);

        /** @var Config $config */
        $config = $container->get(Config::class);

        return new Create($scopeRepo, $config);
    }
}
