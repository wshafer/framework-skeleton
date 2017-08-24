<?php

return [
    'dependencies' => [
        'invokables' => [
        ],

        'factories' => [
            \App\Service\ZendStorageCache::class => \App\Service\ZendStorageCacheFactory::class
        ],
    ],

    'caches' => [
        'Cache\Application' => [
            'adapter' => [
                'name' => \Zend\Cache\Storage\Adapter\Memory::class
            ],
        ],

        'Cache\Doctrine' => [
            'adapter' => [
                'name' => \Zend\Cache\Storage\Adapter\Memory::class
            ],
        ],
    ],
];
