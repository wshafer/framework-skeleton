<?php
declare(strict_types=1);

namespace Auth\Command;

use Auth\Entity\AclResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AclNewResourceCommand extends Command
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
            ->setName('acl:resource:new')
            ->setDescription('Add a new resource to the ACL')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Acl Resource Name'
            )->addArgument(
                'parent',
                InputArgument::OPTIONAL,
                'Parent ACL Resource Name'
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
            ->getRepository(AclResource::class)
            ->findOneBy(['name' => $name]);

        if ($existing) {
            $output->writeln('This resource already exists.');
            return;
        }

        $parent = null;

        if (!empty($parentName)) {
            $parent = $this->entityManager
                ->getRepository(AclResource::class)
                ->findOneBy(['name' => $parentName]);
        }

        if (!empty($parentName) && empty($parent)) {
            $output->writeln('No resource by the name '.$parentName.' exists');
            return;
        }

        $resource = new AclResource();
        $resource->setName($name);

        if (!empty($parent)) {
            $resource->addParent($parent);
        }

        $this->entityManager->persist($resource);
        $this->entityManager->flush();

        $output->writeln('Complete');
    }
}
