<?php declare(strict_types=1);

namespace SeatTest\_Mock\Domain\Menu\Entity;

use Seat\Domain\Menu\Entity\ProductSupplement;
use Seat\Domain\Menu\Entity\ProductSupplementRepository;

class InMemoryProductSupplementRepository implements ProductSupplementRepository
{
    private $supplements = [];

    public function get(string $supplementId): ?ProductSupplement
    {
        return $this->supplements[$supplementId] ?? null;
    }

    public function add(ProductSupplement $supplement): void
    {
        $this->supplements[$supplement->id()] = $supplement;
    }

    /**
     * @return ProductSupplement[]
     */
    public function getByCategoryId(string $categoryId): array
    {
        return array_values(
            array_filter(
                $this->supplements,
                function (ProductSupplement $supplement) use ($categoryId) {
                    return $supplement->categoryId() === $categoryId;
                }
            )
        );
    }
}
