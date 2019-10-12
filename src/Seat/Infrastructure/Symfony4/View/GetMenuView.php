<?php declare(strict_types=1);

namespace Symfony4\View;

use Seat\Domain\Basket\Entity\BasketRepository;
use Seat\Domain\Basket\Service\OrderTypeChecker;
use Seat\Presentation\Menu\GetMenuHtmlViewModel;
use Seat\Presentation\Basket\Model\BasketViewModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony4\Form\BasketType;
use Twig\Environment;

class GetMenuView
{
    private $twig;
    private $formFactory;
    private $basketRepository;
    private $orderTypeChecker;

    public function __construct(
        FormFactoryInterface $formFactory,
        Environment $twig,
        BasketRepository $basketRepository,
        OrderTypeChecker $orderTypeChecker
    ) {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->basketRepository = $basketRepository;
        $this->orderTypeChecker = $orderTypeChecker;
    }

    /** @throws */
    public function generateView(GetMenuHtmlViewModel $viewModel, string $userId)
    {
        $html = $this->twig->render(
            'page/take-away.html.twig',
            [
                'newForm' => $this->formFactory->createBuilder(BasketType::class)->getForm()->createView(),
                'showBasketMobileMenu' => true,
                'basket' => BasketViewModel::fromBasket($this->basketRepository->getUserBasket($userId)),
                'menu' => $viewModel->menu,
                'orderType' => $userId ? $this->orderTypeChecker->getPossibleOrderType($userId) : null,
            ]
        );

        return new Response($html);
    }
}
