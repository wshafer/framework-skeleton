<?php
declare(strict_types=1);

namespace Auth\Config;

use Auth\Exception\MissingConfigException;
use Zend\Config\Config;

class AuthConfig extends Config
{
    protected $hashConfig;

    public function __construct(array $array, $allowModifications = false)
    {
        $authConfig = $array['auth'] ?? [];

        if ($authConfig) {
            parent::__construct($authConfig, $allowModifications);
            return;
        }

        parent::__construct($array, $allowModifications);
    }

    public function getAdapter()
    {
        $adapter = $this->get('adapter');

        if (!$adapter) {
            throw new MissingConfigException('Unable to locate auth adaptor in auth config');
        }

        return $adapter;
    }

    public function getAdapters()
    {
        return $this->get('adapters', new self([]))->toArray();
    }

    public function getIdentity()
    {
        $identity = $this->get('identity');

        if (!$identity) {
            throw new MissingConfigException('Unable to locate Identity to use in auth config');
        }

        return $identity;
    }

    public function getRedirect()
    {
        $redirect = $this->get('redirect');

        if (!$redirect) {
            throw new MissingConfigException('Unable to locate login redirect in auth config');
        }

        return $redirect;
    }

    public function getHashConfig()
    {
        if (!$this->hashConfig) {
            $hashArray = $this->get('hash');
            $this->hashConfig = new HashConfig($hashArray->toArray());
        }

        return $this->hashConfig;
    }
}