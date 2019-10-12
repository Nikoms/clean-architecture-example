<?php declare(strict_types=1);


namespace Seat\Domain\Menu\Entity;

interface CategoryRepository
{
    /**
     * @return Category[]
     */
    public function getCategories(): array;

    public function addCategory(Category $category);
}
