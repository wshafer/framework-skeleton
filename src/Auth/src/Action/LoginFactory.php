<?php
declare(strict_types=1);

namespace Auth\Action;

use Auth\Config\AuthConfig;
use Auth\Exception\MissingConfigException;
use Psr\Container\ContainerInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class LoginFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var AuthConfig $authConfig */
        $authConfig = $container->get(AuthConfig::class);

        $redirect = $authConfig->getRedirect();

        /** @var TemplateRendererInterface $renderer */
        $renderer = $container->get(TemplateRendererInterface::class);

        /** @var AuthenticationService $authService */
        $authService = $container->get(AuthenticationService::class);

        /** @var RouterInterface $router */
        $router = $container->get(RouterInterface::class);

        /** @var AdapterInterface $adapter */
        $adapter = $container->get($authConfig->getAdapter());

        return new Login($renderer, $authService, $adapter, $router, $redirect);
    }
}
