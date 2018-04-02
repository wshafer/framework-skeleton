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
            \Authentication\Command\User\Create::class
                => \Authentication\Command\User\CreateFactory::class,
            \Authentication\Command\User\Scopes::class
                => \Authentication\Command\User\ScopesFactory::class,
            \Authentication\Command\User\Modify::class
                => \Authentication\Command\User\ModifyFactory::class,
            \Authentication\Command\User\Password::class
                => \Authentication\Command\User\PasswordFactory::class,
            \Authentication\Command\User\Delete::class
                => \Authentication\Command\User\DeleteFactory::class,
        ],
    ],

    'console' => [
        'commands' => [
            \Authentication\Command\User\Create::class
                => \Authentication\Command\User\Create::class,
            \Authentication\Command\User\Scopes::class
                => \Authentication\Command\User\Scopes::class,
            \Authentication\Command\User\Modify::class
                => \Authentication\Command\User\Modify::class,
            \Authentication\Command\User\Password::class
                => \Authentication\Command\User\Password::class,
            \Authentication\Command\User\Delete::class
                => \Authentication\Command\User\Delete::class,
        ]
    ]
];
