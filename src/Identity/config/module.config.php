<?php

return [
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'paths'     => [__DIR__ . '/../src/Entity'],
            ],
        ],
    ],

    'dependencies' => [
        'factories' => [
            \Identity\Command\User\Create::class
                => \Identity\Command\User\CreateFactory::class,
            \Identity\Command\User\Scopes::class
                => \Identity\Command\User\ScopesFactory::class,
            \Identity\Command\User\Modify::class
                => \Identity\Command\User\ModifyFactory::class,
            \Identity\Command\User\Password::class
                => \Identity\Command\User\PasswordFactory::class,
            \Identity\Command\User\Delete::class
                => \Identity\Command\User\DeleteFactory::class,
        ],
    ],

    'console' => [
        'commands' => [
            \Identity\Command\User\Create::class
                => \Identity\Command\User\Create::class,
            \Identity\Command\User\Scopes::class
                => \Identity\Command\User\Scopes::class,
            \Identity\Command\User\Modify::class
                => \Identity\Command\User\Modify::class,
            \Identity\Command\User\Password::class
                => \Identity\Command\User\Password::class,
            \Identity\Command\User\Delete::class
                => \Identity\Command\User\Delete::class,
        ]
    ]
];
