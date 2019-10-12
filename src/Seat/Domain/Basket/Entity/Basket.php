<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Entity;

use Seat\Domain\Basket\Model\BasketProduct;

class Basket extends \ArrayObject
{
    private $userId;

    /**
     * @param BasketProduct[] $basketProducts
     */
    public function __construct(string $userId, array $basketProducts)
    {
        parent::__construct($basketProducts);
        $this->userId = $userId;
    }

    public function totalPrice()
    {
        $price = 0;
        /** @var BasketProduct $basketProduct */
        foreach ($this as $basketProduct) {
            $price += $basketProduct->totalPrice();
        }

        return $price;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function isEmpty()
    {
        return $this->count() === 0;
    }

    public function checkSum()
    {
        $content = '';
        /** @var BasketProduct $basketProduct */
        foreach ($this as $basketProduct) {
            $content .= '|'.$basketProduct->id();
        }
        $json = [
            'price' => $this->totalPrice(),
            'content' => $content,
        ];

        return sha1(json_encode($json));
    }
}
