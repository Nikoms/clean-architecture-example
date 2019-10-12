<?php declare(strict_types=1);

namespace Seat\Domain\Menu\Model;

class MenuProduct
{
    private $id;
    private $name;
    private $price;
    private $description;

    public function __construct(string $id, string $name, ?string $description, float $price)
    {

        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
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

    public function description(): ?string
    {
        return $this->description;
    }
}
