<?php declare(strict_types=1);

namespace Seat\Domain\Menu\Model;

class MenuSupplement
{
    private $id;
    private $name;
    private $price;

    public function __construct(string $id, string $name, float $price)
    {

        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function id(): string
    {
        return $this->id;
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
