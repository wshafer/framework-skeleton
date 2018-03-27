<?php

declare(strict_types=1);

namespace OAuth\Command\User;

use Doctrine\ORM\EntityManager;
use OAuth\Config\Config;
use OAuth\Repository\ScopeRepository;
use OAuth\Repository\UserRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Delete extends AbstractUserCommand
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
            ->setName('oauth:user:delete')
            ->setDescription('Delete an existing user')
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
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $output->writeln('Complete');
    }
}
