<?php

return [
    'routes' => [
        'auth:login' => [
            'name'            => 'auth:login',
            'path'            => '/login',
            'middleware'      => \Auth\Action\Login::class,
            'allowed_methods' => ['GET', 'POST'],
        ],
    ],

    'dependencies' => [
        'invokables' => [

        ],
        'factories'  => [
            \Auth\Config\AuthConfig::class => \Auth\Config\AuthConfigFactory::class,
            \Auth\Command\CreateUserCommand::class => \Auth\Command\CreateUserCommandFactory::class,
            \Auth\Command\ChangePasswordCommand::class => \Auth\Command\ChangePasswordCommandFactory::class,
            \Auth\Adapter\AdapterAggregate::class => \Auth\Adapter\AdapterAggregateFactory::class,
            \Auth\Adapter\PasswordAdapter::class => \Auth\Adapter\PasswordAdapterFactory::class,
            \Zend\Authentication\AuthenticationService::class => \Auth\AuthenticationServiceFactory::class,
            \Auth\Action\Login::class => \Auth\Action\LoginFactory::class,
            \Auth\Action\Auth::class => \Auth\Action\AuthFactory::class,
            \Auth\Command\AclNewResourceCommand::class => \Auth\Command\AclNewResourceCommandFactory::class,
            \Auth\Command\AclNewRoleCommand::class => \Auth\Command\AclNewRoleCommandFactory::class,
            \Auth\Command\AclNewPrivilegeCommand::class => \Auth\Command\AclNewPrivilegeCommandFactory::class,
        ],
    ],

    'console' => [
        'commands' => [
            \Auth\Command\CreateUserCommand::class,
            \Auth\Command\ChangePasswordCommand::class,
            \Auth\Command\AclNewPrivilegeCommand::class,
            \Auth\Command\AclNewRoleCommand::class,
            \Auth\Command\AclNewResourceCommand::class,
        ],
    ],

    'templates'    => [
        'paths' => [
            'auth'    => [__DIR__ . '/../templates/auth'],
            'error'  => [__DIR__ . '/../templates/error'],
            'layout' => [__DIR__ . '/../templates/layout'],
        ],
    ],

    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'paths'     => [__DIR__ . '/../src/Entity'],
            ],
        ],
    ],

    'acl' => [
        'defaultRole' => 'guest',
    ],

    'auth' => [
        'adapter' => \Auth\Adapter\AdapterAggregate::class,
        'adapters' => [
            \Auth\Adapter\PasswordAdapter::class,
        ],
        'identity' => \Auth\Entity\User::class,
        'redirect' => 'home',
        'hash' => [
            'algo' => PASSWORD_DEFAULT,
            'options' => [
                'cost' => 16
            ]
        ]
    ],
];
