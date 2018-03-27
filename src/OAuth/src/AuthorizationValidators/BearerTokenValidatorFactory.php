<?php

declare(strict_types=1);

namespace OAuth\AuthorizationValidators;

use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use OAuth\Config\Config;
use OAuth\Entity\AccessToken;
use OAuth\Repository\AccessTokenRepository;
use Psr\Container\ContainerInterface;

class BearerTokenValidatorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('Oauth\Doctrine\EntityManager');

        /** @var AccessTokenRepository $accessTokenRepository */
        $accessTokenRepository = $entityManager->getRepository(AccessToken::class);

        /** @var Config $config */
        $config = $container->get(Config::class);

        $validator = new BearerTokenValidator($accessTokenRepository);
        $validator->setEncryptionKey($config->getPublicKeyPath());

        return $validator;
    }
}
