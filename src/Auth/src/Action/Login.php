<?php
declare(strict_types=1);

namespace Auth\Action;

use Auth\Adapter\PasswordInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class Login implements ServerMiddlewareInterface
{
    private $auth;
    private $authAdapter;
    private $template;
    private $router;
    private $redirect;

    public function __construct(
        TemplateRendererInterface $template,
        AuthenticationService $auth,
        AdapterInterface $authAdapter,
        RouterInterface $router,
        $redirect = null
    ) {
        $this->template    = $template;
        $this->auth        = $auth;
        $this->authAdapter = $authAdapter;
        $this->router      = $router;
        $this->redirect    = $redirect;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if ($this->auth->hasIdentity()) {
            return new RedirectResponse($this->router->generateUri($this->redirect));
        }

        if ($request->getMethod() === 'POST') {
            return $this->authenticate($request);
        }

        return new HtmlResponse($this->template->render('auth::login'));
    }

    public function authenticate(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();

        if (empty($params['username'])) {
            return new HtmlResponse($this->template->render('auth::login', [
                'error' => 'The username cannot be empty',
            ]));
        }

        if (empty($params['password'])) {
            return new HtmlResponse($this->template->render('auth::login', [
                'username' => $params['username'],
                'error'    => 'The password cannot be empty',
            ]));
        }

        if ($this->authAdapter instanceof \Auth\Adapter\AdapterInterface) {
            $this->authAdapter->setUsername($params['username']);
        }

        if ($this->authAdapter instanceof PasswordInterface) {
            $this->authAdapter->setPassword($params['password']);
        }

        $result = $this->auth->authenticate();

        if (!$result->isValid()) {
            return new HtmlResponse($this->template->render('auth::login', [
                'username' => $params['username'],
                'error'    => 'The credentials provided are not valid',
            ]));
        }

        return new RedirectResponse($this->router->generateUri($this->redirect));
    }
}