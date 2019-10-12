<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Model;

class BasketProductOption
{
    private $name;
    private $price;

    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }
}
