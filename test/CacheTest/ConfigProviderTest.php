<?php

namespace CacheTest;

use Cache\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    public function testInvoke()
    {
        $provider = new ConfigProvider();

        $result = $provider();

        $this->assertTrue(is_array($result));
    }
}
