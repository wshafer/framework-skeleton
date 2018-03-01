<?php

namespace OAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OauthRefreshTokens
 *
 * @ORM\Table(name="default_oauth_refresh_tokens", indexes={@ORM\Index(name="idx1_oauth_refresh_tokens", columns={"access_token_id"})})
 * @ORM\Entity
 */
class OauthRefreshTokens
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
     * @var string|null
     *
     * @ORM\Column(name="access_token_id", type="string", length=100, nullable=true)
     */
    private $accessTokenId;

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
