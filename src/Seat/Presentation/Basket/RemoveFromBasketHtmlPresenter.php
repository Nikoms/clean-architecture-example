<?php declare(strict_types=1);

namespace Seat\Presentation\Basket;

use Seat\Domain\Basket\UseCase\RemoveFromBasket\RemoveFromBasketPresenter;
use Seat\Domain\Basket\UseCase\RemoveFromBasket\RemoveFromBasketResponse;

class RemoveFromBasketHtmlPresenter implements RemoveFromBasketPresenter
{
    private $viewModel;

    public function present(RemoveFromBasketResponse $response): void
    {
        $this->viewModel = new RemoveFromBasketHtmlViewModel();
    }

    public function viewModel(): RemoveFromBasketHtmlViewModel
    {
        return $this->viewModel;
    }
}
