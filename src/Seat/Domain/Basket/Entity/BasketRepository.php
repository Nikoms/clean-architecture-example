<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Entity;

use Seat\Domain\Basket\Model\BasketProduct;

interface BasketRepository
{
    public function addToBasket(string $userId, BasketProduct $basketProduct);

    public function getUserBasket(string $userId): Basket;

    public function emptyBasketFor(string $userId): void;

    public function delete(string $basketId, string $userId): void;
}
