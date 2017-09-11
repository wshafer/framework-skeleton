<?php

namespace AuthTest;

use Auth\Action\RouteLock;
use Auth\Action\RouteLockFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Expressive\Router\RouterInterface;

/**
 * @covers \Auth\Action\RouteLockFactory
 */
class RouteLockFactoryTest extends TestCase
{
    /** @var RouteLockFactory */
    protected $factory;

    /** @var \PHPUnit_Framework_MockObject_MockObject|AuthenticationServiceInterface */
    protected $mockAuth;

    /** @var \PHPUnit_Framework_MockObject_MockObject|RouterInterface */
    protected $mockRouter;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ContainerInterface */
    protected $mockContainer;

    public function setup()
    {
        $this->mockRouter = $this->createMock(RouterInterface::class);
        $this->mockAuth = $this->createMock(AuthenticationServiceInterface::class);
        $this->mockContainer = $this->createMock(ContainerInterface::class);

        $map = [
            [AuthenticationServiceInterface::class, $this->mockAuth],
            [RouterInterface::class, $this->mockRouter],
        ];

        $this->mockContainer->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));

        $this->factory = new RouteLockFactory();

        $this->assertInstanceOf(RouteLockFactory::class, $this->factory);
    }

    public function testInvoke()
    {
        $action = $this->factory->__invoke($this->mockContainer);

        $this->assertInstanceOf(RouteLock::class, $action);
    }
}
