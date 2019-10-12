<?php declare(strict_types=1);

namespace Seat\Domain\Order\UseCase\ConfirmBasket;

interface ConfirmBasketPresenter
{
    public function present(ConfirmBasketResponse $response): void;
}
