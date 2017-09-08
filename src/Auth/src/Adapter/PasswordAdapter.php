<?php
declare(strict_types=1);

namespace Auth\Adapter;

use Auth\Config\AuthConfig;
use Auth\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Zend\Authentication\Result;

class PasswordAdapter implements PasswordInterface
{
    protected $username;

    protected $password;

    protected $userRepo;

    protected $entityManager;

    protected $authConfig;

    public function __construct(
        EntityManagerInterface $entityManager,
        EntityRepository $userRepo,
        AuthConfig $authConfig
    ) {
        $this->entityManager = $entityManager;
        $this->userRepo = $userRepo;
        $this->authConfig = $authConfig;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function authenticate()
    {
        /** @var User $user */
        $user = $this->userRepo->findOneBy(['username' => $this->username]);

        $hashConfig = $this->authConfig->getHashConfig();

        if (!$user) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $this->username);
        }

        if (!password_verify($this->password, $user->getPassword())) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, $this->username);
        }

        if (password_needs_rehash($user->getPassword(), $hashConfig->getAlgorithm(), $hashConfig->getAlgorithmOptions())) {
            $newHash = password_hash($this->password, $hashConfig->getAlgorithm(), $hashConfig->getAlgorithmOptions());
            $user->setPassword($newHash);
            $this->entityManager->flush();
        }

        return new Result(Result::SUCCESS, $user);
    }
}
