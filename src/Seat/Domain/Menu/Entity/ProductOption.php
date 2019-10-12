<?php declare(strict_types=1);

namespace Seat\Domain\Menu\Entity;

class ProductOption
{
    private $id;
    private $name;
    private $price;
    private $categoryId;

    public function __construct(string $id, string $categoryId, string $name, float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->categoryId = $categoryId;
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

    public function categoryId(): string
    {
        return $this->categoryId;
    }
}
