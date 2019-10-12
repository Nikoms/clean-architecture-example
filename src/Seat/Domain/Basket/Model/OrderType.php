<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Model;


class OrderType
{
//type: take-away | delivery
//if take-away => take-away-time: 12:00:00

    private $name;
    private $time;

    public static function takeAwayFor($time)
    {
        return new OrderType('take-away', $time);
    }

    public static function delivery()
    {
        return new OrderType('delivery');
    }

    public static function fromString($orderType, $takeAwayTime = null)
    {
        switch ($orderType) {
            case OrderTypeName::$takeAway:
                return OrderType::takeAwayFor($takeAwayTime);
            case OrderTypeName::$delivery:
                return OrderType::delivery();
        }

        throw new \Exception('Invalid order type');
    }

    public function __construct(string $name, ?string $time = null)
    {
        $this->name = $name;
        $this->time = $time;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function time(): ?string
    {
        return $this->time;
    }

    public function isDelivery()
    {
        return $this->name === OrderTypeName::$delivery;
    }

    public function isTakeAway()
    {
        return $this->name === OrderTypeName::$takeAway;
    }
}
