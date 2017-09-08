<?php

return [
    'session_config' => [

    ],

    'session_storage' => [
        'type' => \Zend\Session\Storage\SessionArrayStorage::class
    ],

    'session_containers' => [
        'App\\Session\\Container'
    ],
];
