<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\AddProductToBasket;

use Seat\Domain\Basket\Model\BasketProduct;
use Seat\SharedKernel\Error\Notification;

class AddProductToBasketResponse
{
    private $notification;
    /** @var BasketProduct|null */
    private $basketProduct;

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

    public function setBasketProduct(BasketProduct $basketProduct)
    {
        $this->basketProduct = $basketProduct;
    }

    public function basketProduct(): ?BasketProduct
    {
        return $this->basketProduct;
    }
}
