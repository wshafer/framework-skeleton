<?php

declare(strict_types=1);

namespace OAuth\Entity;

use Database\Entity\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Scope
 *
 * @ORM\Table(
 *     name="users",
 *     indexes={@ORM\Index(name="idx1_users", columns={"email"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="unique_user_email", columns={"email"})}
 * )
 * @ORM\Entity(repositoryClass="OAuth\Repository\UserRepository")
 */
class User implements UserEntityInterface
{
    use TimestampableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", length=11, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    protected $password;

    /**
     * @var Scope[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Scope", inversedBy="users")
     * @ORM\JoinTable(name="user_scopes",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="scope_id", referencedColumnName="id", onDelete="cascade")},
     * )
     */
    protected $scopes;

    /**
     * @var AccessToken
     *
     * @ORM\OneToMany(targetEntity="AccessToken", mappedBy="user")
     */
    protected $accessTokens;

    /**
     * @var AuthCode
     *
     * @ORM\OneToMany(targetEntity="AuthCode", mappedBy="user")
     */
    protected $authCode;

    public function __construct()
    {
        $this->scopes = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
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
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return ArrayCollection|Scope[]
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @param ArrayCollection|Scope[] $scopes
     */
    public function setScopes($scopes): void
    {
        $this->scopes->clear();

        foreach ($scopes as $scope) {
            $this->addScope($scope);
        }
    }

    public function addScope(Scope $scope)
    {
        if ($this->scopes->contains($scope)){
            return;
        }

        $this->scopes->add($scope);
    }

    /**
     * @return AccessToken
     */
    public function getAccessTokens(): AccessToken
    {
        return $this->accessTokens;
    }

    /**
     * @param AccessToken $accessTokens
     */
    public function setAccessTokens(AccessToken $accessTokens): void
    {
        $this->accessTokens = $accessTokens;
    }

    /**
     * @return AuthCode
     */
    public function getAuthCode(): AuthCode
    {
        return $this->authCode;
    }

    /**
     * @param AuthCode $authCode
     */
    public function setAuthCode(AuthCode $authCode): void
    {
        $this->authCode = $authCode;
    }

    /*
     * mandatory methods for oauth below
     */
    public function getIdentifier()
    {
        return $this->getId();
    }
}