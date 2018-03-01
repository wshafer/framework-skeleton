<?php

return [
    'dependencies' => [
        'factories' => [
            'doctrine.entity_manager.orm_default' => [ContainerInteropDoctrine\EntityManagerFactory::class, 'orm_default'],
            \Database\EventListener\DoctrineTablePrefixListener::class => [Database\EventListener\DoctrineTablePrefixListenerFactory::class, 'orm_default'],
            'doctrine.cache.orm_default' =>  \Cache\Service\DoctrineCacheFactory::class,
        ],
    ],

    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'result_cache' => 'orm_default',
                'metadata_cache' => 'orm_default',
                'query_cache' => 'orm_default',
                'hydration_cache' => 'orm_default',
                'auto_generate_proxy_classes' => true,
                'proxy_dir' => 'data/proxies',
                'proxy_namespace' => 'DoctrineEntityProxy',
                'second_level_cache' => [
                    'enabled' => false,
                    'default_lifetime' => 3600,
                    'default_lock_lifetime' => 60,
                    'file_lock_region_directory' => '',
                    'regions' => [],
                ],
                'sql_logger' => null,
            ],
        ],

        'connection' => [
            'orm_default' => [
                'params' => [
                    'url' => 'mysql://root:root@localhost/local',
                ],
            ],
        ],

        'event_manager' => [
            'orm_default' => [
                'subscribers' => [
                    \Database\EventListener\DoctrineTablePrefixListener::class => \Database\EventListener\DoctrineTablePrefixListener::class,
                    \Gedmo\Timestampable\TimestampableListener::class => \Gedmo\Timestampable\TimestampableListener::class,
                ],
            ],
        ],

        'driver' => [
            'orm_default' => [
                'class'     => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache'     => 'orm_default',
                'db_prefix' => 'default_'
            ],
        ],
    ],
];
