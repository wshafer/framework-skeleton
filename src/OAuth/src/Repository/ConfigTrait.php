<?php

declare(strict_types=1);

namespace OAuth\Repository;

use OAuth\Config\Config;
use Psr\Container\ContainerInterface;

trait ConfigTrait
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->container->get(Config::class);
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}