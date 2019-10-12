<?php declare(strict_types=1);

namespace Seat\Domain\Menu\Entity;

interface ProductSupplementRepository
{
    public function get(string $supplementId): ?ProductSupplement;

    public function add(ProductSupplement $supplement): void;

    /**
     * @return ProductSupplement[]
     */
    public function getByCategoryId(string $categoryId): array;
}
