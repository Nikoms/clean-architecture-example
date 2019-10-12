<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Seat\Domain\Menu\UseCase\GetMenu\GetMenu;
use Seat\Presentation\Menu\GetMenuHtmlPresenter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony4\Security\User;
use Symfony4\View\GetMenuView;

/**
 * @Route("/order", name="menu.show")
 */
class ShowOrderMenuController
{
    public function __invoke(GetMenu $getMenu, GetMenuHtmlPresenter $presenter, GetMenuView $view, UserInterface $user = null)
    {
        $userId = $user instanceof User ? $user->getId() : '';
        $getMenu->execute($presenter);

        return $view->generateView($presenter->viewModel(), $userId);
    }
}
