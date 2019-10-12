<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\ShowBasket;

use Seat\Domain\Basket\Entity\Basket;

class ShowBasketResponse
{
    /** @var Basket|null */
    private $basket;

    public function setBasket(?Basket $basket)
    {
        $this->basket = $basket;

        return $this;
    }

    public function basket(): ?Basket
    {
        return $this->basket;
    }
}
