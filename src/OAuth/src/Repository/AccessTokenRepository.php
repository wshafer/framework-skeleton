<?php

declare(strict_types=1);

namespace OAuth\Repository;

use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use OAuth\Entity\AccessToken;
use OAuth\Entity\User;
use OAuth\Exception\AccessTokenNotFoundException;

class AccessTokenRepository extends EntityRepository implements AccessTokenRepositoryInterface
{
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessToken();
        $accessToken->setClient($clientEntity);
        $accessToken->setScope($scopes);

        $userRepository = $this->_em->getRepository(User::class);

        /** @var User $user */
        $user = $userRepository->find($userIdentifier);

        if (!$user) {
            throw new \RuntimeException(
                'Unable to locate user'
            );
        }

        $accessToken->setUser($user);

        return $accessToken;
    }

    /**
     * @param AccessTokenEntityInterface|AccessToken $accessTokenEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $existingToken = $this->findAccessTokenByToken($accessTokenEntity->getIdentifier());

        if (!empty($existingToken)) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        if (!$accessTokenEntity->getId()) {
            $this->_em->persist($accessTokenEntity);
        }

        $this->_em->flush($accessTokenEntity);
    }

    /**
     * @param string $token
     * @return AccessToken|null
     */
    public function findAccessTokenByToken($token)
    {
        /** @var AccessToken $accessToken */
        $accessToken = $this->findOneBy(['token' => $token]);

        if (!$accessToken) {
            throw new AccessTokenNotFoundException(
                'An Access Token by the token id of '.$token.' was not found'
            );
        }

        return $accessToken;
    }

    public function revokeAccessToken($tokenId)
    {
        $accessToken = $this->findAccessTokenByToken($tokenId);
        $accessToken->setRevoked(true);
    }

    public function isAccessTokenRevoked($tokenId)
    {
        try {
            $accessToken = $this->findAccessTokenByToken($tokenId);
        } catch (AccessTokenNotFoundException $e) {
            throw OAuthServerException::accessDenied();
        }

        return $accessToken->isRevoked();
    }
}
