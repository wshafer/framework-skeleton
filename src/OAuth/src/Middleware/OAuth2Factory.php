<?php

declare(strict_types=1);

namespace OAuth\Middleware;

use League\OAuth2\Server\AuthorizationServer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class OAuth2Factory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var AuthorizationServer $server */
        $server = $container->get(AuthorizationServer::class);

        /** @var callable $responseFactory */
        $responseFactory = $container->get(ResponseInterface::class);

        return new OAuth2($server, $responseFactory);
    }
}
