<?php

declare(strict_types=1);

namespace Identity\Command\User;

use Identity\Entity\User;
use Identity\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use OAuth\Config\Config;
use OAuth\Entity\Scope;
use OAuth\Repository\ScopeRepository;

class ScopesFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('Oauth\Doctrine\EntityManager');

        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);

        /** @var ScopeRepository $scopeRepo */
        $scopeRepo = $entityManager->getRepository(Scope::class);

        /** @var Config $config */
        $config = $container->get(Config::class);

        return new Scopes($entityManager, $userRepository, $scopeRepo, $config);
    }
}
