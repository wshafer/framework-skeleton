<?php
declare(strict_types=1);

namespace Auth\Repository;

use Auth\Entity\AclResource;
use Doctrine\ORM\EntityRepository;

class AclResourceRepository extends EntityRepository
{
    /**
     * @return AclResource[]
     */
    public function getTopLevel()
    {
        return $this
            ->createQueryBuilder('resources')
            ->where('resources.parents is empty')
            ->getQuery()
            ->getResult();
    }
}