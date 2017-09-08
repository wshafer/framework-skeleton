<?php
declare(strict_types=1);

namespace Auth\Entity;

use Database\Entity\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Example Entity Class to store a simple timestamp to the database.
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements IdentityInterface
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
    protected $username;

    /**
     * @var string User Name
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var AclRole
     *
     * @ORM\ManyToOne(targetEntity="AclRole", inversedBy="users")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    protected $role;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return AclRole
     */
    public function getRole(): AclRole
    {
        return $this->role;
    }

    /**
     * @param AclRole $role
     */
    public function setRole(AclRole $role)
    {
        $this->role = $role;
    }
}
