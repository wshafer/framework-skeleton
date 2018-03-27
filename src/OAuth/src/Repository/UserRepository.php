<?php

declare(strict_types=1);

namespace OAuth\Repository;

use Database\Repository\ContainerAwareInterface;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use OAuth\Entity\Scope;
use OAuth\Entity\User;
use OAuth\Exception\UserExistsException;
use OAuth\Exception\UserNotFoundException;

class UserRepository extends EntityRepository implements UserRepositoryInterface, ContainerAwareInterface
{
    use ConfigTrait;

    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        $user = null;

        try {
            $user = $this->findOneByEmailAddress($username);
        } catch (UserNotFoundException $e) {}

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            return false;
        }

        $config = $this->getConfig();

        $passwordNeedsRehash = password_needs_rehash(
            $user->getPassword(),
            $config->getPasswordHashAlgorithm(),
            $config->getPasswordHashOptions()
        );

        if (!$passwordNeedsRehash) {
            return $user;
        }

        $user->setPassword(password_hash(
            $password,
            $config->getPasswordHashAlgorithm(),
            $config->getPasswordHashOptions()
        ));

        $this->_em->flush($user);
        return $user;
    }

    public function createNewUser(
        string $email,
        string $password,
        array $allowedScopes
    ) {
        $exists = null;

        /** @var ScopeRepository $scopeRepo */
        $scopeRepo = $this->_em->getRepository(Scope::class);

        try {
            $exists = $this->findOneByEmailAddress($email);
        } catch (UserNotFoundException $e) {}

        if ($exists) {
            throw new UserExistsException(
                'A user with the email address of "'.$email.'" already exists'
            );
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

        foreach ($allowedScopes as $index => $allowedScope) {
            if (!$allowedScope instanceof Scope) {
                $allowedScopes[$index] = $scopeRepo->findOneByName($allowedScope);
            }
        }

        $user->setScopes($allowedScopes);
        $this->_em->persist($user);
        $this->_em->flush($user);
    }

    /**
     * @param string $email
     * @return User
     */
    public function findOneByEmailAddress(string $email)
    {
        /** @var User $user */
        $user = $this->findOneBy(['email' => $email]);

        if (!$user) {
            throw new UserNotFoundException(
                'A user with the email address of '.$email.' was not found'
            );
        }

        return $user;
    }
}