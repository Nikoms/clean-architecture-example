<?php declare(strict_types=1);

namespace Seat\Presentation\Menu;

use Seat\Domain\Menu\UseCase\GetMenu\GetMenuPresenter;
use Seat\Domain\Menu\UseCase\GetMenu\GetMenuResponse;
use Seat\Presentation\Menu\Model\MenuViewModel;

class GetMenuHtmlPresenter implements GetMenuPresenter
{
    private $viewModel;

    public function present(GetMenuResponse $response): void
    {
        $this->viewModel = new GetMenuHtmlViewModel();
        $this->viewModel->menu = MenuViewModel::fromMenuLines($response->menuLines);
    }

    public function viewModel(): GetMenuHtmlViewModel
    {
        return $this->viewModel;
    }
}
