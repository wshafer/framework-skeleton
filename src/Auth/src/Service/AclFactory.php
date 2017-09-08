<?php
declare(strict_types=1);

namespace Auth\Service;

use Auth\Entity\AclPrivilege;
use Auth\Entity\AclResource;
use Auth\Entity\AclRole;
use Auth\Repository\AclPrivilegeRepository;
use Auth\Repository\AclResourcesRepository;
use Auth\Repository\AclRoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Zend\Permissions\Acl\Acl;

class AclFactory
{
    public function __construct(ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.entity_manager.orm_default');

        /** @var AclResourcesRepository $resourceRepository */
        $resourceRepository = $entityManager->getRepository(AclResource::class);

        /** @var AclRoleRepository $roleRepository */
        $roleRepository = $entityManager->getRepository(AclRole::class);

        /** @var AclPrivilegeRepository $privilegesRepository */
        $privilegesRepository = $entityManager->getRepository(AclPrivilege::class);

        $acl = new Acl();

        $resources = $resourceRepository->getTopLevel();

        foreach ($resources as $resource) {
            $this->addResource($resource, $acl);
        }

        $roles = $roleRepository->getTopLevel();

        foreach ($roles as $role) {
            $this->addRole($role, $acl);
        }

        /** @var AclPrivilege[] $privileges */
        $privileges = $privilegesRepository->findAll();

        foreach ($privileges as $privilege) {
            $acl->allow($privilege->getRoles()->toArray(), $privilege->getResource(), $privilege->getAction());
        }

        return $acl;
    }


    public function addRole(AclRole $role, Acl $acl)
    {
        if ($acl->hasRole($role)) {
            return;
        }

        $acl->addRole($role, $role->getParents()->toArray());

        $children = $role->getChildren();

        if (!$children->isEmpty()) {
            foreach ($children as $child) {
                $this->addRole($child, $acl);
            }
        }
    }

    public function addResource(AclResource $resource, Acl $acl)
    {
        if ($acl->hasResource($resource)) {
            return;
        }

        $acl->addResource($resource, $resource->getParents()->toArray());

        $children = $resource->getChildren();

        if (!$children->isEmpty()) {
            foreach ($children as $child) {
                $this->addResource($child, $acl);
            }
        }
    }
}
