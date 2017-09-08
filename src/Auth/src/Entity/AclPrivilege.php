<?php
declare(strict_types=1);

namespace Auth\Entity;

use Database\Entity\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * Example Entity Class to store a simple timestamp to the database.
 *
 * @ORM\Entity(repositoryClass="Auth\Repository\AclPrivilegeRepository")
 * @ORM\Table(
 *     name="acl_privileges",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="privilege_unique",
 *             columns={"action", "resource_id"}
 *         )
 *     }
 * )
 */
class AclPrivilege
{
    use TimestampableTrait;

    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string Privilege Name
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $action;

    /**
     * @var AclResource
     *
     * @ORM\ManyToOne(targetEntity="AclResource", inversedBy="privileges")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     */
    protected $resource;

    /**
     *
     * @var AclRole[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AclRole", mappedBy="privileges")
     */
    protected $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return AclResource
     */
    public function getResource(): AclResource
    {
        return $this->resource;
    }

    /**
     * @param AclResource $resource
     */
    public function setResource(AclResource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return AclRole[]|ArrayCollection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param AclRole $role
     *
     * @return boolean
     */
    public function hasRole(AclRole $role)
    {
        if ($this->roles->isEmpty()) {
            return false;
        }

        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('name', $role->getName()));

        $roles = $this->roles->matching($criteria);

        if ($roles->isEmpty()) {
            return false;
        }

        return true;
    }

    /**
     * @param AclRole[]|ArrayCollection $roles
     */
    public function setRoles($roles)
    {
        $this->roles->clear();

        foreach ($roles as $role) {
            $this->addRole($role);
        }
        $this->roles = $roles;
    }

    public function addRole(AclRole $role)
    {
        $role->addPrivilege($this);
        $this->roles->add($role);
    }

    public function removeRole(AclRole $role)
    {
        $this->roles->removeElement($role);
    }
}
