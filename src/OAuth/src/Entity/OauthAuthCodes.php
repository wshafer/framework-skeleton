<?php

namespace OAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OauthAuthCodes
 *
 * @ORM\Table(name="default_oauth_auth_codes")
 * @ORM\Entity
 */
class OauthAuthCodes
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=100, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="client_id", type="integer", nullable=true)
     */
    private $clientId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="scopes", type="text", length=65535, nullable=true)
     */
    private $scopes;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="revoked", type="boolean", nullable=true)
     */
    private $revoked;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     */
    private $expiresAt;


}
