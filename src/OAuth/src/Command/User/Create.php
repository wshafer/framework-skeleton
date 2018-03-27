<?php

declare(strict_types=1);

namespace OAuth\Command\User;

use OAuth\Exception\InvalidEmailAddress;
use OAuth\Exception\UserExistsException;
use OAuth\Exception\UserNotFoundException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Create extends AbstractUserCommand
{
    /**
     * Configures the command
     */
    protected function configure()
    {
        $this
            ->setName('oauth:user:create')
            ->setDescription('Create a new user')
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'email address'
            );
    }

    /**
     * Executes the current command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $this->getUserEmailAddress($input);
        $secret = $this->getSecret($input, $output, 'password');
        $allowedScopes = $this->getScopes($input, $output);

        $this->userRepository->createNewUser(
            $email,
            $secret,
            $allowedScopes
        );

        $output->writeln('User '.$email.' created successfully');
    }

    protected function getUserEmailAddress(InputInterface $input)
    {
        $email = $input->getArgument('email');

        $filtered = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$filtered) {
            throw new InvalidEmailAddress(
                $email.' is not a valid email address'
            );
        }

        $exists = null;

        try {
            $exists = $this->userRepository->findOneByEmailAddress($filtered);
        } catch (UserNotFoundException $e) {}

        if ($exists) {
            throw new UserExistsException(
                'A user with the email address of "'.$filtered.'" already exists'
            );
        }

        return $filtered;
    }
}
