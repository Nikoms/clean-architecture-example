<?php declare(strict_types=1);

namespace SeatTest\_Mock\Domain\Basket\Entity;

use Seat\Domain\Basket\Entity\Basket;
use Seat\Domain\Basket\Entity\BasketRepository;
use Seat\Domain\Basket\Model\BasketProduct;

class InMemoryBasketRepository implements BasketRepository
{
    /** @var  BasketProduct[][] */
    private $baskets = [];

    public function __construct()
    {
    }

    public function addToBasket(string $userId, BasketProduct $basketProduct)
    {
        if (!array_key_exists($userId, $this->baskets)) {
            $this->baskets[$userId] = [];
        }
        $this->baskets[$userId][] = $basketProduct;
    }

    public function getUserBasket(string $userId): Basket
    {
        if (!array_key_exists($userId, $this->baskets) || count($this->baskets[$userId]) === 0) {
            return new Basket($userId, []);
        }

        return new Basket($userId, $this->baskets[$userId]);
    }

    public function emptyBasketFor(string $userId): void
    {
        unset($this->baskets[$userId]);
    }

    public function delete(string $basketId, string $userId): void
    {
        for ($i = 0; $i < count($this->baskets[$userId]); $i++) {
            if ($this->baskets[$userId][$i]->id() === $basketId) {
                array_splice($this->baskets[$userId], $i, 1);

                return;
            }
        }
    }
}
