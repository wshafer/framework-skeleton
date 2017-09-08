<?php
declare(strict_types=1);

namespace Auth\Entity;

use Database\Entity\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Example Entity Class to store a simple timestamp to the database.
 *
 * @ORM\Entity(repositoryClass="Auth\Repository\AclResourceRepository")
 * @ORM\Table(name="acl_resource")
 */
class AclResource implements ResourceInterface
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
     * @var string Resource Name
     *
     * @ORM\Column(type="string", name="name")
     */
    protected $name;

    /**
     * @var AclPrivilege[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AclPrivilege", mappedBy="privilege")
     */
    protected $privileges;

    /**
     * Parent
     *
     * @var Resource[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AclResource", inversedBy="children", fetch="EAGER")
     * @ORM\JoinTable(
     *     name="acl_resource_parents",
     *     joinColumns={@ORM\JoinColumn(name="resource_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")}
     * )
     */
    protected $parents;

    /**
     * Children
     *
     * @var Resource[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AclResource", mappedBy="parents", fetch="EAGER")
     */
    protected $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->privileges = new ArrayCollection();
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
     * @param AclPrivilege[]|ArrayCollection $privileges
     */
    public function setPrivileges($privileges)
    {
        $this->privileges = $privileges;
    }

    /**
     * @param $action
     * @return AclPrivilege|null
     */
    public function getPrivilegeByAction($action = null)
    {
        $criteria = new Criteria();

        if ($action) {
            $criteria->where(Criteria::expr()->eq('action', $action));
        }

        if ($action === null) {
            $criteria->where(Criteria::expr()->isNull('action'));
        }


        $privileges = $this->privileges->matching($criteria);

        if ($privileges->isEmpty()) {
            return null;
        }

        return $privileges->first();
    }

    /**
     * @return ArrayCollection|Resource[]
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @param ArrayCollection|Resource[] $parents
     */
    public function setParents($parents)
    {
        $this->parents->clear();

        foreach ($parents as $parent) {
            $this->addParent($parent);
        }
    }

    public function addParent(AclResource $resource)
    {
        $this->parents->add($resource);
    }

    /**
     * @return AclResource[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param AclResource[] $children
     */
    public function setChildren(array $children)
    {
        $this->children->clear();

        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    /**
     * @param AclResource $role
     */
    public function addChild(AclResource $role)
    {
        $role->addParent($this);
        $this->children->add($role);
    }

    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId() : string
    {
        return $this->getName();
    }
}
