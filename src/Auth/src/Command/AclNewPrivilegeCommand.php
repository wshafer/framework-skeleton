<?php
declare(strict_types=1);

namespace Auth\Command;

use Auth\Entity\AclPrivilege;
use Auth\Entity\AclResource;
use Auth\Entity\AclRole;
use Auth\Repository\AclPrivilegeRepository;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AclNewPrivilegeCommand extends Command
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
            ->setName('acl:privilege:new')
            ->setDescription('Add a new privilege to the ACL')
            ->addArgument(
                'role',
                InputArgument::REQUIRED,
                'Acl Role Name to add privilege to'
            )->addArgument(
                'action',
                InputArgument::OPTIONAL,
                'The action on the resource to allow'
            )->addArgument(
                'resource',
                InputArgument::OPTIONAL,
                'Acl Resource Name to add privilege to'
            );
    }

    /**
     * Executes the current command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $roleName = $input->getArgument('role');
        $resourceName = $input->getArgument('resource');
        $actionName = $input->getArgument('action');

        try {
            $resource = $this->getResource($resourceName);
            $role = $this->getRole($roleName);
            $privilege = $this->getPrivilege($resource, $actionName);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            return;
        }

        if ($privilege->hasRole($role)) {
            $output->writeln('This privilege already exists.');
            return;
        }

        $privilege->addRole($role);
        $this->entityManager->flush();

        $output->writeln('Complete');
    }

    /**
     * @param AclResource $resource
     * @param $actionName
     *
     * @return AclPrivilege
     */
    protected function getPrivilege(AclResource $resource = null, $actionName=null)
    {
        /** @var AclPrivilegeRepository $privilegeRepo */
        $privilegeRepo = $this->entityManager->getRepository(AclPrivilege::class);

        $resourceName = $resource ? $resource->getName() : null;

        $privilege = $privilegeRepo->getPrivilege($resourceName, $actionName);

        if (!empty($privilege)) {
            return $privilege;
        }

        $privilege = new AclPrivilege();

        if ($actionName) {
            $privilege->setAction($actionName);
        }

        if ($resource) {
            $privilege->setResource($resource);
        }

        $this->entityManager->persist($privilege);

        return $privilege;
    }

    /**
     * @param $resourceName
     *
     * @return AclResource
     *
     * @throws \Exception
     */
    protected function getResource($resourceName = null)
    {
        if (!$resourceName) {
            return null;
        }

        /** @var AclResource $resource */
        $resource = $this->entityManager->getRepository(AclResource::class)
            ->findOneBy(['name' => $resourceName]);

        if (empty($resource)) {
            throw new \Exception('Resource '.$resourceName.' does not exist');
        }

        return $resource;
    }

    /**
     * @param $roleName
     *
     * @return AclRole
     *
     * @throws \Exception
     */
    protected function getRole($roleName)
    {
        /** @var AclRole $role */
        $role = $this->entityManager->getRepository(AclRole::class)
            ->findOneBy(['name' => $roleName]);

        if (empty($role)) {
            throw new \Exception('Role '.$roleName.' does not exist');
        }

        return $role;
    }
}
