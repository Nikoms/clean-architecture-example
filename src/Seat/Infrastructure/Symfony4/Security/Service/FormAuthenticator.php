<?php
declare(strict_types=1);

namespace Symfony4\Security\Service;

use Symfony4\Security\User;
use Seat\Domain\Client\UseCase\Login\Login;
use Seat\Domain\Client\UseCase\Login\LoginPresenter;
use Seat\Domain\Client\UseCase\Login\LoginRequest;
use Seat\Domain\Client\UseCase\Login\LoginResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class FormAuthenticator extends AbstractGuardAuthenticator implements LoginPresenter
{

    private $router;
    /** @var UserPasswordEncoderInterface */
    private $passwordHasher;
    /** @var Login */
    private $login;
    /** @var LoginResponse */
    private $response;

    public function __construct(RouterInterface $router, UserPasswordEncoderInterface $passwordHasher, Login $login)
    {
        $this->router = $router;
        $this->passwordHasher = $passwordHasher;
        $this->login = $login;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        return [
            'email' => $this->getEmail($request),
            'password' => $request->request->get('password', ''),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $this->login->execute(new LoginRequest($credentials['email'], $credentials['password']), $this);
        if ($this->response->notification()->hasError()) {
            throw new CustomUserMessageAuthenticationException(
                $this->response->notification()->getErrors()[0]->message()
            );
        }

        return new User($this->response->client());
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        //All the checks are done in getUser
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('home'));
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        $request->getSession()->set(Security::LAST_USERNAME, $this->getEmail($request));
        $url = $this->router->generate('login');

        return new RedirectResponse($url);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('login'));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe()
    {
        return true;
    }

    private function getEmail(Request $request): string
    {
        return $request->request->get('email', '');
    }

    /**
     * Does the authenticator support the given Request?
     *
     * If this returns false, the authenticator will be skipped.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->getPathInfo() === '/login-check' && $request->isMethod('POST');
    }

    public function present(LoginResponse $response)
    {
        $this->response = $response;
    }
}
