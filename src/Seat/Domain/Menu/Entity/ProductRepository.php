<?php declare(strict_types=1);

namespace Seat\Domain\Menu\Entity;


interface ProductRepository
{
    public function add(Product $product): void;

    public function get(string $id): ?Product;

    /**
     * @return Product[]
     */
    public function getByCategoryId(string $categoryId): array;
}
