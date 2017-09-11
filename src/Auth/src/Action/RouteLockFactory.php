<?php
declare(strict_types=1);

namespace Auth\Action;

use Auth\Exception\MissingConfigException;
use Psr\Container\ContainerInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class RouteLockFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var AuthenticationService $authService */
        $authService = $container->get(AuthenticationServiceInterface::class);

        /** @var RouterInterface $router */
        $router = $container->get(RouterInterface::class);

        return new RouteLock($authService, $router);
    }
}
