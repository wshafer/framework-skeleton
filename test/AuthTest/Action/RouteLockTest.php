<?php

namespace AuthTest;

use Auth\Action\RouteLock;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

class RouteLockTest extends TestCase
{
    /** @var RouteLock */
    protected $action;

    /** @var \PHPUnit_Framework_MockObject_MockObject|AuthenticationServiceInterface */
    protected $mockAuth;

    /** @var \PHPUnit_Framework_MockObject_MockObject|RouterInterface */
    protected $mockRouter;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ServerRequestInterface */
    protected $mockServerRequest;

    /** @var \PHPUnit_Framework_MockObject_MockObject|DelegateInterface */
    protected $mockDelegate;

    public function setup()
    {
        $this->mockServerRequest = $this->createMock(ServerRequestInterface::class);
        $this->mockDelegate = $this->createMock(DelegateInterface::class);
        $this->mockRouter = $this->createMock(RouterInterface::class);
        $this->mockAuth = $this->createMock(AuthenticationServiceInterface::class);

        $this->action = new RouteLock(
            $this->mockAuth,
            $this->mockRouter
        );

        $this->assertInstanceOf(RouteLock::class, $this->action);
    }

    public function testConstructor()
    {
    }

    public function testProcess()
    {
        $this->mockAuth->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(true);

        $this->mockAuth->expects($this->once())
            ->method('getIdentity')
            ->willReturn('my-identity');

        $this->mockRouter->expects($this->never())
            ->method('generateUri');

        $this->mockServerRequest->expects($this->once())
            ->method('withAttribute')
            ->with($this->equalTo('identity'), $this->equalTo('my-identity'))
            ->willReturn($this->mockServerRequest);

        $this->mockDelegate->expects($this->once())
            ->method('process')
            ->with($this->equalTo($this->mockServerRequest))
            ->willReturn(true);

        $response = $this->action->process($this->mockServerRequest, $this->mockDelegate);

        $this->assertTrue($response);
    }

    public function testProcessNoLogin()
    {
        $this->mockAuth->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(false);

        $this->mockAuth->expects($this->never())
            ->method('getIdentity');

        $this->mockRouter->expects($this->once())
            ->method('generateUri')
            ->with('auth:login')
            ->willReturn('/');

        $this->mockServerRequest->expects($this->never())
            ->method('withAttribute');

        $this->mockDelegate->expects($this->never())
            ->method('process');

        $response = $this->action->process($this->mockServerRequest, $this->mockDelegate);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
