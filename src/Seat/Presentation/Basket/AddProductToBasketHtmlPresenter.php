<?php declare(strict_types=1);

namespace Seat\Presentation\Basket;

use Seat\Domain\Basket\UseCase\AddProductToBasket\AddProductToBasketPresenter;
use Seat\Domain\Basket\UseCase\AddProductToBasket\AddProductToBasketResponse;

class AddProductToBasketHtmlPresenter implements AddProductToBasketPresenter
{
    private $viewModel;

    public function present(AddProductToBasketResponse $response): void
    {
        $this->viewModel = new AddProductToBasketHtmlViewModel();
        foreach ($response->notification()->getErrors() as $error) {
            $this->viewModel->addNotification('error', $error->message());
        }
    }

    public function viewModel(): AddProductToBasketHtmlViewModel
    {
        return $this->viewModel;
    }
}
