<?php declare(strict_types=1);

namespace Symfony4\View;

use Seat\Domain\Order\UseCase\ConfirmBasket\ConfirmBasketRequest;
use Seat\Presentation\Basket\ShowBasketHtmlViewModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony4\Form\BasketConfirmationType;
use Twig\Environment;

class ShowBasketView
{
    private $twig;
    private $formFactory;
    private $tokenStorage;
    private $router;
    private $userId;

    public function __construct(Environment $twig, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->userId = is_string($tokenStorage->getToken()->getUser()) ? '' : $tokenStorage->getToken()->getUser()->getId();
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public function generateView(ShowBasketHtmlViewModel $viewModel)
    {
        if ($viewModel->mustRedirectToMenu) {
            return new RedirectResponse($this->router->generate('menu.show'));
        }

        $confirmBasketRequest = (new ConfirmBasketRequest())->withCheckSum($viewModel->basket->checkSum);
        $form = $this->formFactory
            ->createBuilder(BasketConfirmationType::class, $confirmBasketRequest, ['userId' => $this->userId])
            ->getForm()->createView();

        return new Response($this->twig->render('page/basket.html.twig', ['form' => $form, 'basket' => $viewModel->basket]));
    }
}
