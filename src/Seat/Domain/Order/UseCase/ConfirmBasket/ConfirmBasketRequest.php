<?php declare(strict_types=1);

namespace Seat\Domain\Order\UseCase\ConfirmBasket;

class ConfirmBasketRequest
{
    public $userId;
    public $checkSum;
    public $orderTypeName;
    public $takeAwayTime;

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
}
