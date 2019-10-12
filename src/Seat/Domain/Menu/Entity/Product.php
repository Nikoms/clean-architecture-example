<?php declare(strict_types=1);

namespace Seat\Domain\Menu\Entity;

class Product
{
    private $id;
    private $name;
    private $description;
    private $price;
    private $categoryId;

    public function __construct(string $id, string $categoryId, string $name, string $description, float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
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

    public function description(): string
    {
        return $this->description;
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
