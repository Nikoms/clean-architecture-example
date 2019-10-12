<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\ShowBasket;

class ShowBasketRequest
{
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }
}
