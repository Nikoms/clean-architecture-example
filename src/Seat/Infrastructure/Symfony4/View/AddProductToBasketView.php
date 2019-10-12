<?php declare(strict_types=1);

namespace Symfony4\View;

use Seat\Presentation\Basket\AddProductToBasketHtmlViewModel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;

class AddProductToBasketView
{
    private $router;
    private $session;

    public function __construct(RouterInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public function generateView(AddProductToBasketHtmlViewModel $viewModel)
    {
        foreach ($viewModel->notifications as $notification) {
            $this->session->getFlashBag()->add($notification['type'], $notification['message']);
        }

        return new RedirectResponse($this->router->generate('menu.show'));
    }
}
