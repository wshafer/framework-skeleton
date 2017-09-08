<?php
declare(strict_types=1);

namespace Auth\Adapter;

use Auth\Config\AuthConfig;
use Auth\Exception\MissingConfigException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;

class PasswordAdapterFactory
{
   public function __invoke(ContainerInterface $container)
   {
       /** @var AuthConfig $authConfig */
       $authConfig = $container->get(AuthConfig::class);

       /** @var EntityManagerInterface $entityManager */
       $entityManager = $container->get('doctrine.entity_manager.orm_default');

       /** @var EntityRepository $repository */
       $repository = $entityManager->getRepository($authConfig->getIdentity());

       return new PasswordAdapter($entityManager, $repository, $authConfig);
   }
}
