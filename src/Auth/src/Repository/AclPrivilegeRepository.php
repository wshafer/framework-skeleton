<?php
declare(strict_types=1);

namespace Auth\Repository;

use Auth\Entity\AclPrivilege;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class AclPrivilegeRepository extends EntityRepository
{
    /**
     * @return AclPrivilege|null
     */
    public function getPrivilege($resourceName = null, $actionName = null)
    {
        $queryBuilder =  $this
            ->createQueryBuilder('priv');

        if (!empty($resourceName)) {
            $queryBuilder->leftJoin('priv.resource', 'resource');
            $queryBuilder->andWhere('resource.name = :resourceName');
            $queryBuilder->setParameter('resourceName', $resourceName);
        } elseif ($resourceName === null) {
            $queryBuilder->andWhere('priv.resource IS NULL');
        }

        if (!empty($actionName)) {
            $queryBuilder->andWhere('priv.action = :action');
            $queryBuilder->setParameter('action', $actionName);
        } elseif ($actionName === null) {
            $queryBuilder->andWhere('priv.action IS NULL');
        }

        try {
            return $queryBuilder->getQuery()->setMaxResults(1)->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
