<?php

return [
    'dependencies' => [
        'invokables' => [
        ],

        'factories' => [
            \Cache\Service\ZendStorageCache::class => \Cache\Service\ZendStorageCacheFactory::class
        ],
    ],
];
