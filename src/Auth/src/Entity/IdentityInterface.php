<?php
declare(strict_types=1);

namespace Auth\Entity;

interface IdentityInterface
{
    public function getId(): int;
    public function getUsername(): string;
    public function setUsername(string $username);
    public function getPassword(): string;
    public function setPassword(string $password);
    public function getRole(): AclRole;
    public function setRole(AclRole $role);
}
