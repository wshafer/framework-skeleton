<?php

return [
    'dependencies' => [
        'factories' => [
            \Session\Middleware\SessionHandler::class => \Session\Middleware\SessionHandlerFactory::class
        ],
    ],

    'middleware_pipeline' => [
        'session' => [
            'middleware' => \Session\Middleware\SessionHandler::class,
            'priority' => 10000,
        ]
    ]
];
