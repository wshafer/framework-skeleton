<?php

declare(strict_types=1);

namespace Authentication\Command\User;

use Authentication\Entity\User;
use Authentication\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use OAuth\Config\Config;
use OAuth\Entity\Scope;
use OAuth\Repository\ScopeRepository;

class DeleteFactory
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

        return new Delete($entityManager, $userRepository, $scopeRepo, $config);
    }
}
