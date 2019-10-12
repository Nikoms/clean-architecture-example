<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Error;
use Seat\Domain\Menu\Entity\ProductOption;
use Seat\Domain\Menu\Entity\ProductOptionRepository;

class DoctrineProductOptionRepository extends ServiceEntityRepository implements ProductOptionRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryOption::class);
    }

    /**
     * @return ProductOption
     */
    public function get(string $productOptionId): ?ProductOption
    {
        /** @var CategoryOption $option */
        $option = $this->find($productOptionId);
        if ($option === null) {
            return null;
        }

        return new ProductOption(
            $option->getId().'',
            $option->getCategory()->getId().'',
            $option->getName(),
            $option->getPrice()
        );
    }

    public function add(ProductOption $productOption): void
    {
        throw new Error('Method "'.__METHOD__.'" to implement');
    }

    /**
     * @return ProductOption[]
     */
    public function getByCategoryId(string $categoryId): array
    {
        /** @var CategoryOption $option */
        $options = $this->findBy(['category' => $categoryId], array('order' => 'ASC'));

        return array_map(
            function (CategoryOption $option) {
                return new ProductOption(
                    $option->getId().'',
                    $option->getCategory()->getId().'',
                    $option->getName(),
                    $option->getPrice()
                );
            },
            $options
        );
    }
}
