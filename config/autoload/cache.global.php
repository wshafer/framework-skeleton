<?php

return [
    'caches' => [
        'Cache\Application' => [
            'adapter' => [
                'name' => \Zend\Cache\Storage\Adapter\Memory::class
            ],
        ],

        'Cache\Doctrine' => [
            'adapter' => [
                'name' => \Zend\Cache\Storage\Adapter\Memory::class
            ],
        ],
    ],
];
