<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Seat\Domain\Basket\UseCase\ShowBasket\ShowBasket;
use Seat\Domain\Basket\UseCase\ShowBasket\ShowBasketRequest;
use Seat\Domain\Order\UseCase\ConfirmBasket\ConfirmBasket;
use Seat\Domain\Order\UseCase\ConfirmBasket\ConfirmBasketRequest;
use Seat\Presentation\Basket\ShowBasketHtmlPresenter;
use Seat\Presentation\Order\ConfirmBasketHtmlPresenter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony4\Security\User;
use Symfony4\View\ConfirmBasketView;
use Symfony4\View\ShowBasketView;

/**
 * @Route("/basket", name="basket")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 * @ParamConverter("nullableRequest", options={"form":"Symfony4\Form\BasketConfirmationType"})
 */
class ShowBasketController
{
    public function __invoke(
        ConfirmBasket $confirmBasket,
        ConfirmBasketHtmlPresenter $presenter,
        ConfirmBasketView $view,

        ShowBasket $showBasket,
        ShowBasketHtmlPresenter $showBasketPresenter,
        ShowBasketView $showBasketView,

        ?ConfirmBasketRequest $nullableRequest,
        UserInterface $user = null
    ) {
        /** @var User $user */

        if ($nullableRequest === null) {
            $showBasket->execute(new ShowBasketRequest($user->getId()), $showBasketPresenter);

            return $showBasketView->generateView($showBasketPresenter->viewModel());
        }

        $confirmBasket->execute($nullableRequest->withUserId($user->getId()), $presenter);

        return $view->generateView($presenter->viewModel());
    }

}
