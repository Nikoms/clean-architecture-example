<?php declare(strict_types=1);

namespace SeatTest\Domain\Basket\Entity;

use Seat\Domain\Menu\Entity\Product;

class ProductBuilder
{
    private $id = 'product-id';
    private $name = 'Jambon beurre';
    private $description = 'Du jambon et du beurre';
    private $price = 3.2;
    private $categoryId = 'category-id';

    public static function aProduct()
    {
        return new ProductBuilder();
    }

    public function id(string $id)
    {
        $this->id = $id;

        return $this;
    }

    public function name(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function description(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function price(float $price)
    {
        $this->price = $price;

        return $this;
    }

    public function categoryId(string $categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function build()
    {
        return new Product($this->id, $this->categoryId, $this->name, $this->description, $this->price);
    }
}
