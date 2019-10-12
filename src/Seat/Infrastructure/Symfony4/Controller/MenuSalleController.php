<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/menu-salle", name="menu.salle")
 */
class MenuSalleController
{
    public function __invoke(Environment $twig)
    {
        return new Response($twig->render('page/menu-salle.html.twig'));
    }
}
