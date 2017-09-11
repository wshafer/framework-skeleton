<?php

namespace AuthTest;

use Auth\Action\Login;
use Auth\Action\LoginFactory;
use Auth\Config\AuthConfig;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * @covers \Auth\Action\LoginFactory
 */
class LoginFactoryTestTest extends TestCase
{
    /** @var LoginFactory */
    protected $factory;

    /** @var \PHPUnit_Framework_MockObject_MockObject|AuthenticationServiceInterface */
    protected $mockAuth;

    /** @var \PHPUnit_Framework_MockObject_MockObject|TemplateRendererInterface */
    protected $mockRenderer;

    /** @var \PHPUnit_Framework_MockObject_MockObject|AdapterInterface */
    protected $mockAuthAdaptor;

    /** @var \PHPUnit_Framework_MockObject_MockObject|RouterInterface */
    protected $mockRouter;

    /** @var \PHPUnit_Framework_MockObject_MockObject|AuthConfig */
    protected $mockConfig;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ContainerInterface */
    protected $mockContainer;

    public function setup()
    {
        $this->mockRouter = $this->createMock(RouterInterface::class);
        $this->mockAuthAdaptor = $this->createMock(AdapterInterface::class);
        $this->mockAuth = $this->createMock(AuthenticationServiceInterface::class);
        $this->mockRenderer = $this->createMock(TemplateRendererInterface::class);
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->mockConfig = $this->getMockBuilder(AuthConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $map = [
            [AuthConfig::class, $this->mockConfig],
            [TemplateRendererInterface::class, $this->mockRenderer],
            [AuthenticationServiceInterface::class, $this->mockAuth],
            [RouterInterface::class, $this->mockRouter],
            [AdapterInterface::class, $this->mockAuthAdaptor],
        ];

        $this->mockContainer->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));

        $this->factory = new LoginFactory();

        $this->assertInstanceOf(LoginFactory::class, $this->factory);
    }

    public function testInvoke()
    {
        $this->mockConfig->expects($this->once())
            ->method('getRedirect')
            ->willReturn('/');

        $this->mockConfig->expects($this->once())
            ->method('getAdapter')
            ->willReturn(AdapterInterface::class);

        $action = $this->factory->__invoke($this->mockContainer);

        $this->assertInstanceOf(Login::class, $action);
    }
}
