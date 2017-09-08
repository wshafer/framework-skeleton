<?php
declare(strict_types=1);

namespace Auth;

use Auth\Adapter\PasswordAdapter;
use Auth\Exception\MissingConfigException;
use Psr\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;

class AuthenticationServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $adapterServiceName = $config['auth']['adapter'] ?? null;

        if (empty($adapterServiceName)) {
            throw new MissingConfigException(
                'Unable to locate auth adapter in config'
            );
        }

        $adaptor = $container->get($adapterServiceName);

        return new AuthenticationService(null, $adaptor);
    }
}
