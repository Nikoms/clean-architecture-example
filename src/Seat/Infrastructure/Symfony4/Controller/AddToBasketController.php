<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Symfony4\Security\User;
use Symfony4\View\AddProductToBasketView;
use Seat\Domain\Basket\UseCase\AddProductToBasket\AddProductToBasket;
use Seat\Domain\Basket\UseCase\AddProductToBasket\AddProductToBasketRequest;
use Seat\Presentation\Basket\AddProductToBasketHtmlPresenter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/basket/add", name="basket.add",methods={"POST"})
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 * @ParamConverter("nullableRequest", options={"form":"Symfony4\Form\BasketType"})
 */
class AddToBasketController
{
    public function __invoke(
        AddProductToBasket $addProductToBasket,
        AddProductToBasketHtmlPresenter $presenter,
        AddProductToBasketView $view,
        AddProductToBasketRequest $nullableRequest,
        UserInterface $user = null
    ) {
        /** @var User $user */
        $addProductToBasket->execute($nullableRequest->withUserId($user->getId()), $presenter);

        return $view->generateView($presenter->viewModel());
    }
}
