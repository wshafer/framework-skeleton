<?php
declare(strict_types=1);

namespace Auth\Command;

use Auth\Entity\AclResource;
use Auth\Entity\AclRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AclNewRoleCommand extends Command
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this
            ->setName('acl:role:new')
            ->setDescription('Add a new role to the ACL')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Acl Role Name'
            )->addArgument(
                'parent',
                InputArgument::OPTIONAL,
                'Parent ACL Role Name'
            );
    }

    /**
     * Executes the current command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $parentName = $input->getArgument('parent');

        $existing = $this->entityManager
            ->getRepository(AclRole::class)
            ->findOneBy(['name' => $name]);

        if ($existing) {
            $output->writeln('This role already exists.');
            return;
        }

        $parent = null;

        if (!empty($parentName)) {
            $parent = $this->entityManager
                ->getRepository(AclRole::class)
                ->findOneBy(['name' => $parentName]);
        }

        if (!empty($parentName) && empty($parent)) {
            $output->writeln('No role by the name '.$parentName.' exists');
            return;
        }

        $role = new AclRole();
        $role->setName($name);

        if (!empty($parent)) {
            $role->addParent($parent);
        }

        $this->entityManager->persist($role);
        $this->entityManager->flush();

        $output->writeln('Complete');
    }
}
