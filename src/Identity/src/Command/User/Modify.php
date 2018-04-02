<?php

declare(strict_types=1);

namespace Identity\Command\User;

use Identity\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use OAuth\Config\Config;
use OAuth\Exception\InvalidEmailAddress;
use OAuth\Exception\UserExistsException;
use OAuth\Exception\UserNotFoundException;
use OAuth\Repository\ScopeRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Modify extends AbstractUserCommand
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
            ->setName('identity:user:modify')
            ->setDescription('Modify an existing users email address')
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'Client Id'
            )
            ->addOption(
                'email',
                null,
                InputOption::VALUE_REQUIRED,
                'New Email Address'
            );
    }

    /**
     * Executes the current command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->getUser($input);

        $newEmail = $input->getOption('email');

        $this->isEmailValid($newEmail);

        $user->setEmail($newEmail);

        $this->entityManager->flush($user);

        $output->writeln('<info>Email updated to: '.$newEmail.'</info>');
        $output->writeln('Complete');
    }

    protected function isEmailValid(string $newEmail)
    {
        $filtered = filter_var($newEmail, FILTER_VALIDATE_EMAIL);

        if (!$filtered) {
            throw new InvalidEmailAddress(
                $newEmail.' is not a valid email address'
            );
        }

        try {
            $this->userRepository->findOneByEmailAddress($newEmail);
        } catch (UserNotFoundException $e) {
            return true;
        }

        throw new UserExistsException(
            'A user with the email address of "'.$newEmail.'" already exists'
        );
    }
}
