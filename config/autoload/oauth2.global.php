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
    'authentication' => [
        'private_key' => __DIR__ . '/../data/private.key',
        'public_key' => __DIR__ . '/../data/public.key',
        'encryption_key' => 'bhT9/A3eB0b3HFIxEFK9pPOszfUiFhnJwPZkJpzdCl4=',
        'access_token_expire' => 'P1D', // 1 day in DateInterval format
        'refresh_token_expire' => 'P1M', // 1 month in DateInterval format
        'auth_code_expire' => 'PT10M', // 10 minutes in DateInterval format
        'pdo' => [
            'dsn' => '',
            'username' => '',
            'password' => ''
        ],
    ],
];
