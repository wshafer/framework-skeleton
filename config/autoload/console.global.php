<?php

return [
    'dependencies' => [
        'invokables' => [
            \App\Command\GreetCommand::class => \App\Command\GreetCommand::class,
        ],

        'factories' => [
            \WShafer\SwooleExpressive\Command\SwooleRunnerCommand::class
                => \WShafer\SwooleExpressive\Command\SwooleRunnerCommandFactory::class,
        ],
    ],

    'console' => [
        'commands' => [
            \App\Command\GreetCommand::class => \App\Command\GreetCommand::class,
            \WShafer\SwooleExpressive\Command\SwooleRunnerCommand::class => \WShafer\SwooleExpressive\Command\SwooleRunnerCommand::class,
        ],
    ],
];
