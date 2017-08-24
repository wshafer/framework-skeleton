<?php

return [
    'dependencies' => [
        'invokables' => [
            App\Command\GreetCommand::class => App\Command\GreetCommand::class,
        ],

        'factories' => [
        ],
    ],

    'console' => [
        'commands' => [
            App\Command\GreetCommand::class,
        ],
    ],
];
