<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Symfony4\Doctrine\Basket as BasketEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Seat\Domain\Basket\Entity\Basket;
use Seat\Domain\Basket\Entity\BasketRepository;
use Seat\Domain\Basket\Model\BasketProduct;

class DoctrineBasketRepository extends ServiceEntityRepository implements BasketRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BasketEntity::class);
    }

    public function addToBasket(string $userId, BasketProduct $basketProduct)
    {
        $basketEntity = new BasketEntity();
        $basketEntity->setId($basketProduct->id());
        $basketEntity->setUserId($userId);
        $basketEntity->setContent(BasketProductJson::fromBasketProduct($basketProduct)->json());
        $this->getEntityManager()->persist($basketEntity);
        $this->getEntityManager()->flush($basketEntity);
    }

    public function getUserBasket(string $userId): Basket
    {
        /** @var BasketEntity[] $basketProducts */
        $basketProducts = $this->findBy(['userId' => $userId]);

        $convertToObject = function (BasketEntity $basket) {
            return (new BasketProductJson($basket->content()))->basketProduct($basket->id().'');
        };

        $lines = array_map($convertToObject, $basketProducts);

        return new Basket($userId, $lines);
    }

    public function emptyBasketFor(string $userId): void
    {
        $this->createQueryBuilder('b')
            ->delete()
            ->where('b.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function delete(string $basketId, string $userId): void
    {
        $this->createQueryBuilder('b')
            ->delete()
            ->where('b.userId = :userId')
            ->andWhere('b.id = :basketId')
            ->setParameter('userId', $userId)
            ->setParameter('basketId', $basketId)
            ->getQuery()
            ->getResult();
    }
}
