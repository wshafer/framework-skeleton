<?php
declare(strict_types=1);

namespace Auth\Adapter;

interface PasswordInterface extends AdapterInterface
{
    public function setPassword(string $password);
}
