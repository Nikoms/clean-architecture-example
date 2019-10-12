<?php declare(strict_types=1);

namespace Seat\Domain\Order\UseCase\ConfirmBasket;

use Seat\SharedKernel\Error\Notification;

class ConfirmBasketResponse
{
    private $notification;

    public function __construct()
    {
        $this->notification = new Notification();
    }

    public function addError(string $fieldName, string $error)
    {
        $this->notification->addError($fieldName, $error);
    }

    public function notification(): Notification
    {
        return $this->notification;
    }
}
