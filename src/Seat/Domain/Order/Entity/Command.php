<?php declare(strict_types=1);

namespace Seat\Domain\Order\Entity;

use DateTimeImmutable;
use Seat\Domain\Basket\Entity\Basket;
use Seat\Domain\Basket\Model\OrderType;

class Command
{
    private $id;
    private $userId;
    private $basket;
    private $date;
    private $orderType;

    public function __construct(string $id, string $userId, OrderType $orderType, Basket $basket, DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->basket = $basket;
        $this->date = $date;
        $this->orderType = $orderType;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function basket(): Basket
    {
        return $this->basket;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function orderType(): OrderType
    {
        return $this->orderType;
    }
}
