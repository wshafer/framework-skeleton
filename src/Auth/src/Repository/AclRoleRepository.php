<?php
declare(strict_types=1);

namespace Auth\Repository;

use Auth\Entity\AclRole;
use Doctrine\ORM\EntityRepository;

class AclRoleRepository extends EntityRepository
{
    /**
     * @return AclRole[]
     */
    public function getTopLevel()
    {
        return $this
            ->createQueryBuilder('roles')
            ->where('roles.parents is empty')
            ->getQuery()
            ->getResult();
    }
}