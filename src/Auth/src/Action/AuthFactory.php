<?php
declare(strict_types=1);

namespace Auth\Action;

use Auth\Exception\MissingConfigException;
use Psr\Container\ContainerInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class AuthFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var AuthenticationService $authService */
        $authService = $container->get(AuthenticationService::class);

        /** @var RouterInterface $router */
        $router = $container->get(RouterInterface::class);

        return new Auth($authService, $router);
    }
}
