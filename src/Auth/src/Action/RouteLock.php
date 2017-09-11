<?php
declare(strict_types=1);

namespace Auth\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

class RouteLock implements ServerMiddlewareInterface
{
    protected $auth;

    protected $router;

    public function __construct(
        AuthenticationServiceInterface $auth,
        RouterInterface $router
    ) {
        $this->auth = $auth;
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if (! $this->auth->hasIdentity()) {
            return new RedirectResponse($this->router->generateUri('auth:login'));
        }

        $identity = $this->auth->getIdentity();
        return $delegate->process($request->withAttribute('identity', $identity));
    }
}
