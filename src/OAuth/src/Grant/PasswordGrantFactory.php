<?php

declare(strict_types=1);

namespace OAuth\Grant;

use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Grant\PasswordGrant;
use OAuth\Config\Config;
use OAuth\Entity\RefreshToken;
use OAuth\Entity\User;
use OAuth\Repository\RefreshTokenRepository;
use OAuth\Repository\UserRepository;
use Psr\Container\ContainerInterface;

class PasswordGrantFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('Oauth\Doctrine\EntityManager');

        /** @var UserRepository $userRepo */
        $userRepo = $entityManager->getRepository(User::class);

        /** @var RefreshTokenRepository $refreshTokenRepo */
        $refreshTokenRepo = $entityManager->getRepository(RefreshToken::class);

        /** @var Config $oauthConfig */
        $oauthConfig = $container->get(Config::class);

        $grant = new PasswordGrant(
            $userRepo,
            $refreshTokenRepo
        );

        $grant->setRefreshTokenTTL($oauthConfig->getRefreshTokenExpireInterval());

        return $grant;
    }
}
