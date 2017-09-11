<?php

namespace AuthTest;

use Auth\Action\Login;
use Auth\Adapter\AdapterInterface as AuthAdapterInterface;
use Auth\Adapter\PasswordInterface as AuthPasswordAdapterInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class LoginTest extends TestCase
{
    /** @var Login */
    protected $action;

    /** @var \PHPUnit_Framework_MockObject_MockObject|AuthenticationServiceInterface */
    protected $mockAuth;

    /** @var \PHPUnit_Framework_MockObject_MockObject|TemplateRendererInterface */
    protected $mockRenderer;

    /** @var \PHPUnit_Framework_MockObject_MockObject|AdapterInterface */
    protected $mockAuthAdaptor;

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
        $this->mockAuthAdaptor = $this->createMock(AdapterInterface::class);
        $this->mockAuth = $this->createMock(AuthenticationServiceInterface::class);
        $this->mockRenderer = $this->createMock(TemplateRendererInterface::class);

        $this->action = new Login(
            $this->mockRenderer,
            $this->mockAuth,
            $this->mockAuthAdaptor,
            $this->mockRouter,
            '/'
        );

        $this->assertInstanceOf(Login::class, $this->action);
    }

    public function testConstructor()
    {
    }

    public function testProcess()
    {
        $this->mockAuth->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(false);

        $this->mockRouter->expects($this->never())
            ->method('generateUri');

        $this->mockServerRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $this->mockServerRequest->expects($this->never())
            ->method('getParsedBody');

        $this->mockAuth->expects($this->never())
            ->method('authenticate');

        $this->mockRenderer->expects($this->once())
            ->method('render')
            ->with('auth::login')
            ->willReturn('This is a test');

        $this->mockDelegate->expects($this->never())
            ->method('process');

        $response = $this->action->process($this->mockServerRequest, $this->mockDelegate);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertContains('This is a test', $response->getBody()->getContents());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testProcessAlreadyLoggedIn()
    {
        $this->mockAuth->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(true);

        $this->mockRouter->expects($this->once())
            ->method('generateUri')
            ->with('/')
            ->willReturn('/');

        $this->mockServerRequest->expects($this->never())
            ->method('getMethod');

        $this->mockServerRequest->expects($this->never())
            ->method('getParsedBody');

        $this->mockAuth->expects($this->never())
            ->method('authenticate');

        $this->mockRenderer->expects($this->never())
            ->method('render');

        $this->mockDelegate->expects($this->never())
            ->method('process');

        $response = $this->action->process($this->mockServerRequest, $this->mockDelegate);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testProcessPostNoUserName()
    {
        $this->mockAuth->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(false);

        $this->mockRouter->expects($this->never())
            ->method('generateUri');

        $this->mockServerRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->mockServerRequest->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([]);

        $this->mockAuth->expects($this->never())
            ->method('authenticate');

        $this->mockRenderer->expects($this->once())
            ->method('render')
            ->with('auth::login', $this->arrayHasKey('error'))
            ->willReturn('This is an error test');

        $this->mockDelegate->expects($this->never())
            ->method('process');

        $response = $this->action->process($this->mockServerRequest, $this->mockDelegate);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertContains('This is an error test', $response->getBody()->getContents());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testProcessPostNoPassword()
    {
        $this->mockAuth->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(false);

        $this->mockRouter->expects($this->never())
            ->method('generateUri');

        $this->mockServerRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->mockServerRequest->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'username' => 'my-user'
            ]);

        $this->mockAuth->expects($this->never())
            ->method('authenticate');

        $this->mockRenderer->expects($this->once())
            ->method('render')
            ->with('auth::login', $this->arrayHasKey('error'))
            ->willReturn('This is a no password error test');

        $this->mockDelegate->expects($this->never())
            ->method('process');

        $response = $this->action->process($this->mockServerRequest, $this->mockDelegate);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertContains('This is a no password error test', $response->getBody()->getContents());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testProcessPostValidAuth()
    {
        $this->mockAuth->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(false);

        $this->mockRouter->expects($this->once())
            ->method('generateUri')
            ->with('/')
            ->willReturn('/');

        $this->mockServerRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->mockServerRequest->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'username' => 'my-user',
                'password' => 'my-password'
            ]);

        $this->mockAuth->expects($this->once())
            ->method('authenticate')
            ->willReturn(new Result(true, 'user'));

        $this->mockRenderer->expects($this->never())
            ->method('render');

        $this->mockDelegate->expects($this->never())
            ->method('process');

        $response = $this->action->process($this->mockServerRequest, $this->mockDelegate);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testProcessPostInvalidAuth()
    {
        $this->mockAuth->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(false);

        $this->mockRouter->expects($this->never())
            ->method('generateUri');

        $this->mockServerRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->mockServerRequest->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'username' => 'my-user',
                'password' => 'my-password'
            ]);

        $this->mockAuth->expects($this->once())
            ->method('authenticate')
            ->willReturn(new Result(false, 'user'));

        $this->mockRenderer->expects($this->once())
            ->method('render')
            ->with('auth::login', $this->arrayHasKey('error'))
            ->willReturn('This is an invalid login test');

        $this->mockDelegate->expects($this->never())
            ->method('process');

        $response = $this->action->process($this->mockServerRequest, $this->mockDelegate);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertContains('This is an invalid login test', $response->getBody()->getContents());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testProcessPostValidAuthSettingUserName()
    {
        $this->mockAuthAdaptor = $this->createMock(AuthAdapterInterface::class);

        $this->action = new Login(
            $this->mockRenderer,
            $this->mockAuth,
            $this->mockAuthAdaptor,
            $this->mockRouter,
            '/'
        );

        $this->assertInstanceOf(Login::class, $this->action);

        $this->mockAuthAdaptor->expects($this->once())
            ->method('setUsername')
            ->with('my-user')
            ->willReturn(true);

        $this->mockAuth->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(false);

        $this->mockRouter->expects($this->once())
            ->method('generateUri')
            ->with('/')
            ->willReturn('/');

        $this->mockServerRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->mockServerRequest->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'username' => 'my-user',
                'password' => 'my-password'
            ]);

        $this->mockAuth->expects($this->once())
            ->method('authenticate')
            ->willReturn(new Result(true, 'user'));

        $this->mockRenderer->expects($this->never())
            ->method('render');

        $this->mockDelegate->expects($this->never())
            ->method('process');

        $response = $this->action->process($this->mockServerRequest, $this->mockDelegate);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testProcessPostValidAuthSettingPassword()
    {
        $this->mockAuthAdaptor = $this->createMock(AuthPasswordAdapterInterface::class);

        $this->action = new Login(
            $this->mockRenderer,
            $this->mockAuth,
            $this->mockAuthAdaptor,
            $this->mockRouter,
            '/'
        );

        $this->assertInstanceOf(Login::class, $this->action);

        $this->mockAuthAdaptor->expects($this->once())
            ->method('setUsername')
            ->with('my-user')
            ->willReturn(true);

        $this->mockAuthAdaptor->expects($this->once())
            ->method('setPassword')
            ->with('my-password')
            ->willReturn(true);

        $this->mockAuth->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(false);

        $this->mockRouter->expects($this->once())
            ->method('generateUri')
            ->with('/')
            ->willReturn('/');

        $this->mockServerRequest->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->mockServerRequest->expects($this->once())
            ->method('getParsedBody')
            ->willReturn([
                'username' => 'my-user',
                'password' => 'my-password'
            ]);

        $this->mockAuth->expects($this->once())
            ->method('authenticate')
            ->willReturn(new Result(true, 'user'));

        $this->mockRenderer->expects($this->never())
            ->method('render');

        $this->mockDelegate->expects($this->never())
            ->method('process');

        $response = $this->action->process($this->mockServerRequest, $this->mockDelegate);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
