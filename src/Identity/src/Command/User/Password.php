<?php

declare(strict_types=1);

namespace Identity\Command\User;

use Identity\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use OAuth\Config\Config;
use OAuth\Repository\ScopeRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Password extends AbstractUserCommand
{
    protected $entityManager;

    public function __construct(
        EntityManager $entityManager,
        UserRepository $userRepository,
        ScopeRepository $scopeRepository,
        Config $config
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($userRepository, $scopeRepository, $config);
    }


    /**
     * Configures the command
     */
    protected function configure()
    {
        $this
            ->setName('identity:user:password')
            ->setDescription('Change user password')
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'user email address'
            );
    }

    /**
     * Executes the current command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->getUser($input);
        $secret = $this->getSecret($input, $output, 'password');
        $user->setPassword($secret);
        $this->entityManager->flush($user);
        $output->writeln('Complete');
    }
}
