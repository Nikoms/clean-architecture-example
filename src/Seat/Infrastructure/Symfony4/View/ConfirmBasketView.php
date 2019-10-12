<?php declare(strict_types=1);

namespace Symfony4\View;

use Seat\Presentation\Order\ConfirmBasketHtmlViewModel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class ConfirmBasketView
{
    private $twig;
    private $session;
    private $router;

    public function __construct(Environment $twig, Session $session, RouterInterface $router)
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->router = $router;
    }

    /** @throws */
    public function generateView(ConfirmBasketHtmlViewModel $viewModel)
    {
        if (empty($viewModel->errors)) {
            return new Response($this->twig->render('page/order-done.html.twig'));
        }

        foreach ($viewModel->errors as $error) {
            $this->session->getFlashBag()->add('error', $error);
        }

        return new RedirectResponse($this->router->generate('basket'));
    }
}
