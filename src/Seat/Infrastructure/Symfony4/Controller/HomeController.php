<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/", name="home")
 */
class HomeController
{
    const NUMBER_OF_PICS = 12;

    public function __invoke(Environment $twig)
    {
        $images = [];
        for ($i = 1; $i <= self::NUMBER_OF_PICS; $i++) {
            $images[] = "/gallery/genval{$i}.jpg";
        }

        return new Response(
            $twig->render('page/home.html.twig', ['images' => $images])
        );
    }
}
