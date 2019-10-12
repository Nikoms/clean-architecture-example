<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/login-check", name="login_check", methods={"POST"})
 */
class LoginCheckController
{
    public function __invoke()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
