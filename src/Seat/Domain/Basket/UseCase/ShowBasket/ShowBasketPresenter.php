<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\ShowBasket;

interface ShowBasketPresenter
{
    public function present(ShowBasketResponse $response): void;
}
