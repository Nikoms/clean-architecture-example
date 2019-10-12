<?php declare(strict_types=1);

namespace Seat\Presentation\Basket;

use Seat\Presentation\Basket\Model\BasketViewModel;
use Seat\Domain\Basket\UseCase\ShowBasket\ShowBasketPresenter;
use Seat\Domain\Basket\UseCase\ShowBasket\ShowBasketResponse;

class ShowBasketHtmlPresenter implements ShowBasketPresenter
{

    private $viewModel;

    public function present(ShowBasketResponse $response): void
    {
        $this->viewModel = new ShowBasketHtmlViewModel();
        if ($response->basket()->isEmpty()) {
            $this->viewModel->mustRedirectToMenu = true;
        } else {
            $this->viewModel->basket = BasketViewModel::fromBasket($response->basket());
        }
    }

    public function viewModel(): ShowBasketHtmlViewModel
    {
        return $this->viewModel;
    }

}
