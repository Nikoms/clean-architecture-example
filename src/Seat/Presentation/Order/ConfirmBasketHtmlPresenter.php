<?php declare(strict_types=1);

namespace Seat\Presentation\Order;

use Seat\Domain\Order\UseCase\ConfirmBasket\ConfirmBasketPresenter;
use Seat\Domain\Order\UseCase\ConfirmBasket\ConfirmBasketResponse;

class ConfirmBasketHtmlPresenter implements ConfirmBasketPresenter
{
    private $viewModel;

    public function present(ConfirmBasketResponse $response): void
    {
        $this->viewModel = new ConfirmBasketHtmlViewModel();
        if ($response->notification()->hasError()) {
            foreach ($response->notification()->getErrors() as $error) {
                $this->viewModel->errors[] = $error->message();
            }
        }
    }

    public function viewModel(): ConfirmBasketHtmlViewModel
    {
        return $this->viewModel;
    }
}
