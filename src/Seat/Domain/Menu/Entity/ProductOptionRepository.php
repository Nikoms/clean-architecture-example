<?php declare(strict_types=1);

namespace Seat\Domain\Menu\Entity;

interface ProductOptionRepository
{
    /**
     * @return ProductOption
     */
    public function get(string $productOptionId): ?ProductOption;

    /**
     * @return ProductOption[]
     */
    public function getByCategoryId(string $categoryId): array;

    public function add(ProductOption $productOption): void;
}
