<?php declare(strict_types=1);

namespace SeatTest\_Mock\Domain\Menu\Entity;

use Seat\Domain\Menu\Entity\Product;
use Seat\Domain\Menu\Entity\ProductRepository;

class InMemoryProductRepository implements ProductRepository
{

    /** @var Product[] */
    private $products = [];

    public function __construct()
    {
    }

    public function add(Product $product): void
    {
        $this->products[$product->id()] = $product;
    }

    public function get(string $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    /**
     * @return Product[]
     */
    public function getByCategoryId(string $categoryId): array
    {
        return array_values(
            array_filter(
                $this->products,
                function (Product $product) use ($categoryId) {
                    return $product->categoryId() === $categoryId;
                }
            )
        );
    }
}
