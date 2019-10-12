<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Symfony4\Doctrine\Product as ProductEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Error;
use Seat\Domain\Menu\Entity\Product;
use Seat\Domain\Menu\Entity\ProductRepository;

class DoctrineProductRepository extends ServiceEntityRepository implements ProductRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductEntity::class);
    }

    public function add(Product $product): void
    {
        throw new Error('Method "'.__METHOD__.'" to implement');
    }

    public function get(string $id): ?Product
    {
        /** @var ProductEntity $productEntity */
        $productEntity = $this->find($id);
        if ($productEntity === null) {
            return null;
        }

        return new Product(
            $productEntity->getId().'',
            $productEntity->getCategory()->getId().'',
            $productEntity->getName(),
            $productEntity->getDescription().'',
            $productEntity->getPrice()
        );
    }

    /**
     * @return Product[]
     */
    public function getByCategoryId(string $categoryId): array
    {
        /** @var ProductEntity[] $products */
        $products = $this->findBy(['category' => $categoryId]);

        return array_map(
            function (ProductEntity $product) {
                return new Product(
                    $product->getId().'',
                    $product->getCategory()->getId().'',
                    $product->getName(),
                    $product->getDescription().'',
                    $product->getPrice()
                );
            },
            $products
        );
    }
}
