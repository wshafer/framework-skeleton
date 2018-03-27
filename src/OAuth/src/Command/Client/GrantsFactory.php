<?php

declare(strict_types=1);

namespace OAuth\Command\Client;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use OAuth\Config\Config;
use OAuth\Entity\Client;
use OAuth\Entity\Scope;
use OAuth\Repository\ClientRepository;
use OAuth\Repository\ScopeRepository;

class GrantsFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('Oauth\Doctrine\EntityManager');

        /** @var ClientRepository $clientRepo */
        $clientRepo = $entityManager->getRepository(Client::class);

        /** @var ScopeRepository $scopeRepo */
        $scopeRepo = $entityManager->getRepository(Scope::class);

        /** @var Config $config */
        $config = $container->get(Config::class);

        return new Grants($entityManager,$clientRepo, $scopeRepo, $config);
    }
}
