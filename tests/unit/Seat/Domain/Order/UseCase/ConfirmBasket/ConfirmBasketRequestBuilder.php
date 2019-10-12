<?php declare(strict_types=1);

namespace SeatTest\Domain\Order\UseCase\ConfirmBasket;

use Seat\Domain\Client\Entity\Client;
use Seat\Domain\Basket\Entity\Basket;
use Seat\Domain\Order\UseCase\ConfirmBasket\ConfirmBasketRequest;

class ConfirmBasketRequestBuilder
{
    private $userId = '';
    private $checkSum = '';
    private $orderTypeName = 'delivery';
    private $takeAwayTime = null;

    public static function aConfirmBasket()
    {
        return new self();
    }

    public function build()
    {
        $request = new ConfirmBasketRequest();
        $request->userId = $this->userId;
        $request->checkSum = $this->checkSum;
        $request->orderTypeName = $this->orderTypeName;
        $request->takeAwayTime = $this->takeAwayTime;

        return $request;
    }

    public function withUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function withCheckSum($checkSum)
    {
        $this->checkSum = $checkSum;

        return $this;
    }

    public function withOrderTypeName($orderTypeName)
    {
        $this->orderTypeName = $orderTypeName;

        return $this;
    }

    public function withTakeAwayTime($takeAwayTime)
    {
        $this->takeAwayTime = $takeAwayTime;

        return $this;
    }

    public function forBasket(Basket $basket)
    {
        $this->checkSum = $basket->checkSum();

        return $this;
    }

    public function takeAwayAt(string $takeAwayTime)
    {
        $this->orderTypeName = 'take-away';
        $this->takeAwayTime = $takeAwayTime;

        return $this;
    }

    public function forClient(Client $client)
    {
        $this->userId = $client->id();

        return $this;
    }
}
