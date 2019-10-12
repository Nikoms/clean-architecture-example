<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/logout", name="logout")
 */
class LogoutController
{

    public function __invoke()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
