<?php declare(strict_types=1);

namespace SeatTest\_Mock\Domain\Menu\Entity;

use Seat\Domain\Menu\Entity\Category;
use Seat\Domain\Menu\Entity\CategoryRepository;

class InMemoryCategoryRepository implements CategoryRepository
{
    private $categories = [];

    public function __construct()
    {
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    }
}
