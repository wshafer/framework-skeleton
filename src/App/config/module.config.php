<?php

return [
    'dependencies' => [
        'invokables' => [
            \App\Action\PingAction::class => \App\Action\PingAction::class,
        ],
        'factories'  => [
            \App\Action\HomePageAction::class => \App\Action\HomePageFactory::class,
        ],
    ],

    'templates'    => [
        'paths' => [
            'app'    => [__DIR__ . '/../templates/app'],
            'error'  => [__DIR__ . '/../templates/error'],
            'layout' => [__DIR__ . '/../templates/layout'],
        ],
    ],

    'caches' => [
        'Cache\Application' => [
            'adapter' => [
                'name' => \Zend\Cache\Storage\Adapter\Memory::class
            ],
        ],
    ],
];
