<?php

namespace OAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OauthClients
 *
 * @ORM\Table(name="default_oauth_clients", indexes={@ORM\Index(name="idx1_oauth_clients", columns={"user_id"})})
 * @ORM\Entity
 */
class OauthClients
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="secret", type="string", length=100, nullable=true)
     */
    private $secret;

    /**
     * @var string|null
     *
     * @ORM\Column(name="redirect", type="string", length=255, nullable=true)
     */
    private $redirect;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="personal_access_client", type="boolean", nullable=true)
     */
    private $personalAccessClient;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="password_client", type="boolean", nullable=true)
     */
    private $passwordClient;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="revoked", type="boolean", nullable=true)
     */
    private $revoked;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


}
