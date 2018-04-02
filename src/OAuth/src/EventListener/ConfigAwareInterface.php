<?php

namespace OAuth\EventListener;

use OAuth\Config\Config;

interface ConfigAwareInterface
{
    public function getConfig() : Config;
    public function setConfig(Config $config);
}
