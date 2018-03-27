<?php

declare(strict_types=1);

namespace OAuth\Command\User;

use OAuth\Command\CommandAbstract;
use OAuth\Config\Config;
use OAuth\Entity\User;
use OAuth\Repository\ScopeRepository;
use OAuth\Repository\UserRepository;
use Symfony\Component\Console\Input\InputInterface;

class AbstractUserCommand extends CommandAbstract
{
    /** @var UserRepository */
    protected $userRepository;

    public function __construct(
        UserRepository $userRepository,
        ScopeRepository $scopeRepository,
        Config $config
    ) {
        $this->userRepository = $userRepository;
        parent::__construct($scopeRepository, $config);
    }

    /**
     * @param InputInterface $input
     * @return User
     */
    protected function getUser(InputInterface $input)
    {
        $email = $input->getArgument('email');
        return $this->userRepository->findOneByEmailAddress($email);
    }
}
