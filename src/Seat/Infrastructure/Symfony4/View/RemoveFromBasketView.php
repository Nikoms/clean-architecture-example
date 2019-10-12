<?php declare(strict_types=1);

namespace Symfony4\View;

use Seat\Presentation\Basket\RemoveFromBasketHtmlViewModel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RemoveFromBasketView
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function generateView(?string $redirect, RemoveFromBasketHtmlViewModel $viewModel)
    {
        return new RedirectResponse($redirect ?? $this->router->generate('menu.show'));
    }
}
