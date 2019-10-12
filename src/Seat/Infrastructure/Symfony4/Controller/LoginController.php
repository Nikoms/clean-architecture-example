<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

/**
 * @Route("/login", name="login")
 */
class LoginController
{
    public function __invoke(Environment $twig, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $response = $twig->render(
            'page/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );

        return new Response($response);
    }
}
