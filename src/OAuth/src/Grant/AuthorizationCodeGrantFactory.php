<?php

declare(strict_types=1);

namespace OAuth\Grant;

use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use OAuth\Config\Config;
use OAuth\Entity\AuthCode;
use OAuth\Entity\RefreshToken;
use OAuth\Repository\AuthCodeRepository;
use OAuth\Repository\RefreshTokenRepository;
use Psr\Container\ContainerInterface;

class AuthorizationCodeGrantFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('Oauth\Doctrine\EntityManager');

        /** @var AuthCodeRepository $authCodeRepo */
        $authCodeRepo = $entityManager->getRepository(AuthCode::class);

        /** @var RefreshTokenRepository $refreshTokenRepo */
        $refreshTokenRepo = $entityManager->getRepository(RefreshToken::class);

        /** @var Config $oauthConfig */
        $oauthConfig = $container->get(Config::class);

        $grant = new AuthCodeGrant(
            $authCodeRepo,
            $refreshTokenRepo,
            $oauthConfig->getAuthCodeExpireInterval()
        );

        $grant->setRefreshTokenTTL($oauthConfig->getRefreshTokenExpireInterval());

        return $grant;
    }
}
