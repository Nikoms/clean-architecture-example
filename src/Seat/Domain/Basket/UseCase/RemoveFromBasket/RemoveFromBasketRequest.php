<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\RemoveFromBasket;

class RemoveFromBasketRequest
{
    public $userId;
    public $basketId;

    public function __construct($userId, $basketId)
    {
        $this->userId = $userId;
        $this->basketId = $basketId;
    }
}
