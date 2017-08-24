<?php

namespace App\EventListener;

use ContainerInteropDoctrine\AbstractFactory;
use Psr\Container\ContainerInterface;

class DoctrineEventSubscriberFactory extends AbstractFactory
{
    protected function createWithConfig(ContainerInterface $container, $configKey)
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $prefix = '';

        if (!empty($config['doctrine']['driver'][$configKey]['db_prefix'])) {
            $prefix = $config['doctrine']['driver'][$configKey]['db_prefix'];
        }

        return new DoctrineEventSubscriber($prefix);
    }

    /**
     * Not used here
     *
     * @param string $configKey
     * @return null
     */
    protected function getDefaultConfig($configKey)
    {
        return null;
    }
}
