<?php
declare(strict_types=1);

namespace Auth\Adapter;

use Auth\Config\AuthConfig;
use Auth\Exception\InvalidConfigException;
use Auth\Exception\MissingConfigException;
use Psr\Container\ContainerInterface;
use Zend\Authentication\Adapter\AdapterInterface;

class AdapterAggregateFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var AuthConfig $authConfig */
        $authConfig = $container->get(AuthConfig::class);

        $adaptersConfig = $authConfig->getAdapters();

        if (empty($adaptersConfig)) {
            throw new MissingConfigException(
                'Unable to locate adapters in config'
            );
        }

        $adapters = [];

        foreach ($adaptersConfig as $adapterToGet) {
            $adapter = $container->get($adapterToGet);

            if (!$adapter || !$adapter instanceof AdapterInterface) {
                throw new InvalidConfigException($adapterToGet.' is invalid or not found');
            }

            $adapters[] = $adapter;
        }

        return new AdapterAggregate($adapters);
    }
}
