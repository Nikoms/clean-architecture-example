<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\RemoveFromBasket;

interface RemoveFromBasketPresenter
{
    public function present(RemoveFromBasketResponse $response): void;
}
