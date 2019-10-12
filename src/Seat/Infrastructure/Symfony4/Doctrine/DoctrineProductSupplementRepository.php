<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Error;
use Seat\Domain\Menu\Entity\ProductSupplement;
use Seat\Domain\Menu\Entity\ProductSupplementRepository;

class DoctrineProductSupplementRepository extends ServiceEntityRepository implements ProductSupplementRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorySupplement::class);
    }

    public function get(string $supplementId): ?ProductSupplement
    {
        /** @var CategorySupplement $supplement */
        $supplement = $this->find($supplementId);
        if ($supplement === null) {
            return null;
        }

        return new ProductSupplement(
            $supplement->getId().'',
            $supplement->getCategory()->getId().'',
            $supplement->getName(),
            $supplement->getPrice()
        );
    }

    public function add(ProductSupplement $supplement): void
    {
        throw new Error('Method "'.__METHOD__.'" to implement');
    }

    /**
     * @return ProductSupplement[]
     */
    public function getByCategoryId(string $categoryId): array
    {
        /** @var CategorySupplement $option */
        $supplements = $this->findBy(['category' => $categoryId], array('order' => 'ASC'));

        return array_map(
            function (CategorySupplement $supplement) {
                return new ProductSupplement(
                    $supplement->getId().'',
                    $supplement->getCategory()->getId().'',
                    $supplement->getName(),
                    $supplement->getPrice()
                );
            },
            $supplements
        );
    }
}
