<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\RemoveFromBasket;

class RemoveFromBasketResponse
{
    private $isDone = false;

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function setIsDone($isDone)
    {
        $this->isDone = $isDone;

        return $this;
    }
}
