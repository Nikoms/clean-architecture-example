<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Symfony4\Security\User;
use Symfony4\View\RemoveFromBasketView;
use Seat\Domain\Basket\UseCase\RemoveFromBasket\RemoveFromBasket;
use Seat\Domain\Basket\UseCase\RemoveFromBasket\RemoveFromBasketRequest;
use Seat\Presentation\Basket\RemoveFromBasketHtmlPresenter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/basket/{basketId}/delete", name="basket.delete",methods={"POST"})
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class RemoveFromBasketController
{
    public function __invoke(
        RemoveFromBasket $removeFromBasket,
        RemoveFromBasketHtmlPresenter $presenter,
        RemoveFromBasketView $view,
        Request $request,
        $basketId,
        UserInterface $user = null
    ) {
        /** @var User $user */
        $removeFromBasket->execute(new RemoveFromBasketRequest($user->getId(), $basketId), $presenter);

        return $view->generateView($request->request->get('redirect'), $presenter->viewModel());
    }
}
