<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Symfony4\Doctrine\Command as CommandEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Error;
use Ramsey\Uuid\Uuid;
use Seat\Domain\Order\Entity\Command;
use Seat\Domain\Order\Entity\CommandRepository;
use Seat\Domain\Basket\Model\BasketProduct;

class DoctrineCommandRepository extends ServiceEntityRepository implements CommandRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandEntity::class);
    }

    /**
     * @return Command[]
     */
    public function getTodayList(): array
    {
        throw new Error('Method "'.__METHOD__.'" to implement');
    }

    public function add(Command $command): void
    {
        /** @var BasketProduct $basketProduct */
        foreach ($command->basket() as $basketProduct) {
            $commandEntity = new CommandEntity();
            $commandEntity->setId(Uuid::uuid4()->toString());
            $commandEntity->setUserId($command->userId());
            $commandEntity->setDate($command->date());
            $commandEntity->setOrderType($command->orderType());
            $commandEntity->setContent(BasketProductJson::fromBasketProduct($basketProduct)->json());
            $this->getEntityManager()->persist($commandEntity);
        }
        $this->getEntityManager()->flush();
    }
}
