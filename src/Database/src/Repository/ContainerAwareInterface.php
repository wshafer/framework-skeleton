<?php

namespace Database\Repository;

use Psr\Container\ContainerInterface;

interface ContainerAwareInterface
{
    public function setContainer(ContainerInterface $container) : void;
}
