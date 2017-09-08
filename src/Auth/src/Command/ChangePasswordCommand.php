<?php
declare(strict_types=1);

namespace Auth\Command;

use Auth\Config\AuthConfig;
use Auth\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangePasswordCommand extends Command
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
            ->setName('auth:change-password')
            ->setDescription('Change a users password')
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

        /** @var User $user */
        $user = $this->entityManager->getRepository($this->authConfig->getIdentity())->findOneBy(['username' => $userName]);

        if (!$user) {
            $output->writeln('Unable to locate user: '.$userName);
            return;
        }

        $hashConfig = $this->authConfig->getHashConfig();
        $hash = password_hash($password, $hashConfig->getAlgorithm(), $hashConfig->getAlgorithmOptions());

        $user->setPassword($hash);
        $this->entityManager->flush();

        $output->writeln('Complete');
    }
}
