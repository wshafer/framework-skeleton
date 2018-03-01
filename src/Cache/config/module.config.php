<?php

return [
    'caches' => [
        'default' => [
            'type'      => 'void',         // Required : Type of adapter
//            'namespace' => 'my-namespace', // Optional : Namespace
//            'prefix'    => 'prefix_',      // Optional : Prefix.  If a Namespace is configured and the adapter supports it, the Namespace will me used instead.
//            'logger'    => 'my-logger',    // Optional : PSR-1 Logger Service Name
//            'options'   => [],             // Optional : Adapter Specific Options
        ],
    ],
    'dependencies' => [
        'invokables' => [
        ],

        'factories' => [
            'Cache\Doctrine' => \WShafer\PSR11PhpCache\PhpCacheFactory::class,
            'Cache' => \Cache\Service\DoctrineCacheFactory::class,
        ],
    ],
];
