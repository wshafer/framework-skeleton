<?php

namespace Database\Repository;

interface ConfigAwareInterface
{
    public function setConfig(array $config) : void;
}
