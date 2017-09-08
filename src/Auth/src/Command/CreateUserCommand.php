<?php
declare(strict_types=1);

namespace Auth\Command;

use Auth\Config\AuthConfig;
use Auth\Entity\IdentityInterface;
use Auth\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected $entityManager;

    protected $authConfig;

    public function __construct(EntityManagerInterface $entityManager, AuthConfig $authConfig)
    {
        $this->entityManager = $entityManager;
        $this->authConfig    = $authConfig;

        parent::__construct();
    }

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this
            ->setName('auth:create-user')
            ->setDescription('Create a new user')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'Password'
            );
    }

    /**
     * Executes the current command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userName = $input->getArgument('username');
        $password = $input->getArgument('password');

        $identityClass = $this->authConfig->getIdentity();

        $existing = $this->entityManager->getRepository($identityClass)->findOneBy(['username' => $userName]);

        if ($existing) {
            $output->writeln('This user already exists.');
            return;
        }



        /** @var IdentityInterface $user */
        $user = new $identityClass;
        $user->setUsername($userName);

        $hashConfig = $this->authConfig->getHashConfig();
        $hash = password_hash($password, $hashConfig->getAlgorithm(), $hashConfig->getAlgorithmOptions());

        $user->setPassword($hash);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Complete');
    }
}
