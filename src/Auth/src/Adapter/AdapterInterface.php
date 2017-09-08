<?php
declare(strict_types=1);

namespace Auth\Adapter;

use Zend\Authentication\Adapter\AdapterInterface as ZendAdapterInterface;

interface AdapterInterface extends ZendAdapterInterface
{
    public function setUsername(string $username);
}
