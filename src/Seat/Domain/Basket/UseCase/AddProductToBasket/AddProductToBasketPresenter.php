<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\AddProductToBasket;

interface AddProductToBasketPresenter
{
    public function present(AddProductToBasketResponse $response): void;
}
