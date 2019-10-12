<?php declare(strict_types=1);

namespace SeatTest\_Mock\Domain\Menu\Entity;

use Seat\Domain\Menu\Entity\ProductOption;
use Seat\Domain\Menu\Entity\ProductOptionRepository;

class InMemoryProductOptionRepository implements ProductOptionRepository
{
    private $options = [];

    public function get(string $productOptionId): ?ProductOption
    {
        return $this->options[$productOptionId] ?? null;
    }

    public function add(ProductOption $productOption): void
    {
        $this->options[$productOption->id()] = $productOption;
    }

    /**
     * @return ProductOption[]
     */
    public function getByCategoryId(string $categoryId): array
    {
        return array_values(
            array_filter(
                $this->options,
                function (ProductOption $productOption) use ($categoryId) {
                    return $productOption->categoryId() === $categoryId;
                }
            )
        );
    }
}
