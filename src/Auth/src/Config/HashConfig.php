<?php
declare(strict_types=1);

namespace Auth\Config;

use Auth\Exception\MissingConfigException;
use Zend\Config\Config;

class HashConfig extends Config
{
    public function getAlgorithm()
    {
        $algo = $this->get('algo');

        if (!$algo) {
            throw new MissingConfigException('Unable to locate Hash Algorithm in auth config');
        }

        return $algo;
    }

    public function getAlgorithmOptions()
    {
        return $this->get('options', new self([]))->toArray();
    }
}
