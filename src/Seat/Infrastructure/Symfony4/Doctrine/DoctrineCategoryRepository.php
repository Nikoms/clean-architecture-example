<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Error;
use Symfony4\Doctrine\Category as CategoryEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Seat\Domain\Menu\Entity\Category;
use Seat\Domain\Menu\Entity\CategoryRepository;

class DoctrineCategoryRepository extends ServiceEntityRepository implements CategoryRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryEntity::class);
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        $categories = $this->findBy([], array('order' => 'ASC'));

        return array_map(
            function (CategoryEntity $category) {
                return new Category($category->getId().'', $category->getName(), $category->getDescription());
            },
            $categories
        );
    }

    public function addCategory(Category $category)
    {
        throw new Error('Method "'.__METHOD__.'" to implement');
    }
}
