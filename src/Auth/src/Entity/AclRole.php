<?php
declare(strict_types=1);

namespace Auth\Entity;

use Database\Entity\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Example Entity Class to store a simple timestamp to the database.
 *
 * @ORM\Entity(repositoryClass="Auth\Repository\AclRoleRepository")
 * @ORM\Table(name="acl_role")
 */
class AclRole implements RoleInterface
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
     * @var string User Name
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Parent
     *
     * @var AclPrivilege[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AclPrivilege", inversedBy="roles")
     * @ORM\JoinTable(
     *     name="acl_role_privileges",
     *     joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="privilege_id", referencedColumnName="id")}
     * )
     */
    protected $privileges;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="role")
     */
    protected $users;

    /**
     * Parent
     *
     * @var AclRole[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AclRole", inversedBy="children")
     * @ORM\JoinTable(
     *     name="acl_role_parents",
     *     joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")}
     * )
     */
    protected $parents;

    /**
     * Children
     *
     * @var AclRole[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AclRole", mappedBy="parents")
     */
    protected $children;

    public function __construct()
    {
        $this->parents = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->privileges = new ArrayCollection();
        $this->users = new ArrayCollection();
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return AclPrivilege[]|ArrayCollection
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * @param AclPrivilege[] $privileges
     */
    public function setPrivileges(array $privileges)
    {
        $existing = $this->privileges;

        foreach ($existing as $item) {
            $item->removeRole($this);
        }

        $this->privileges->clear();

        foreach ($privileges as $privilege) {
            $this->addPrivilege($privilege);
        }
    }

    public function addPrivilege(AclPrivilege $privilege)
    {
        $this->privileges->add($privilege);
    }

    /**
     * @return AclRole[]|ArrayCollection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @param AclRole[] $parents
     */
    public function setParents(array $parents)
    {
        $this->parents->clear();

        foreach ($parents as $parent)
        {
            $this->addParent($parent);
        }
    }

    public function addParent(AclRole $role)
    {
        $this->parents->add($role);
    }

    /**
     * @return AclRole[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param AclRole[] $children
     */
    public function setChildren(array $children)
    {
        $this->children->clear();

        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    /**
     * @param AclRole $role
     */
    public function addChild(AclRole $role)
    {
        $role->addParent($this);
        $this->children->add($role);
    }

    /**
     * @return string
     */
    public function getRoleId() : string
    {
        return $this->getName();
    }
}
