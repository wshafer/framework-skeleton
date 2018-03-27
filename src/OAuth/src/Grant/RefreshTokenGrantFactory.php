<?php

declare(strict_types=1);

namespace OAuth\Grant;

use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use OAuth\Config\Config;
use OAuth\Entity\RefreshToken;
use OAuth\Repository\RefreshTokenRepository;
use Psr\Container\ContainerInterface;

class RefreshTokenGrantFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('Oauth\Doctrine\EntityManager');

        /** @var RefreshTokenRepository $refreshTokenRepo */
        $refreshTokenRepo = $entityManager->getRepository(RefreshToken::class);

        /** @var Config $oauthConfig */
        $oauthConfig = $container->get(Config::class);

        $grant = new RefreshTokenGrant($refreshTokenRepo);

        $grant->setRefreshTokenTTL($oauthConfig->getRefreshTokenExpireInterval());

        return $grant;
    }
}
