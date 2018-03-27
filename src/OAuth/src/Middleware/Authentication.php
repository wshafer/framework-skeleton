<?php
declare(strict_types=1);

namespace OAuth\Middleware;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Authentication implements MiddlewareInterface
{
    /**
     * @var ResourceServer
     */
    protected $server;

    protected $responseFactory;

    /**
     * @param ResourceServer $server
     */
    public function __construct(
        ResourceServer $server,
        callable $responseFactory
    ) {
        $this->server = $server;
        $this->responseFactory = function () use ($responseFactory) : ResponseInterface {
            return $responseFactory();
        };
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Create a new response for the request
        $response = ($this->responseFactory)();

        try {
            $request = $this->server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($response);
        }

        // Pass the request and response on to the next responder in the chain
        return $handler->handle($request);
    }
}
