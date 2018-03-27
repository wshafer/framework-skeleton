<?php
/**
 * To generate a private key run this command:
 * openssl genrsa -out private.key 1024
 *
 * To generate the encryption key use this command:
 * php -r 'echo base64_encode(random_bytes(32)), PHP_EOL;'
 *
 * The expire values must be a valid DateInterval format
 * @see http://php.net/manual/en/class.dateinterval.php
 */
return [
    'oauth2' => [
        'privateKeyPath'             => __DIR__ . '/../../data/private.key',
        'publicKeyPath'              => __DIR__ . '/../../data/public.key',
        'encryptionKeyPath'          => __DIR__ . '/../../data/encryption.key',
        'accessTokenExpireInterval'  => 'P1D',   // 1 day in DateInterval format
        'refreshTokenExpireInterval' => 'P1M',   // 1 month in DateInterval format
        'authCodeExpireInterval'     => 'PT10M', // 10 minutes in DateInterval format
        'grants' => [
            // Grants list should be [identifierName] => serviceName
            'authorization_code' => 'authorization_code',
            'client_credentials' => 'client_credentials',
            'implicit'           => 'implicit',
            'password'           => 'password',
            'refresh_token'      => 'refresh_token',
        ]
    ],
];
