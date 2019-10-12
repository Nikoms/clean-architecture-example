<?php declare(strict_types=1);

namespace Seat\Presentation\Basket\Model;

use Seat\Domain\Basket\Entity\Basket;
use Seat\Domain\Basket\Model\BasketProduct;
use Seat\Domain\Basket\Model\BasketProductSupplement;

class BasketViewModel
{
    public $totalPrice;
    public $checkSum;
    public $products;

    public function __construct($totalPrice, $checkSum, $products)
    {
        $this->totalPrice = $totalPrice;
        $this->checkSum = $checkSum;
        $this->products = $products;
    }

    public static function fromBasket(Basket $basket)
    {
        $lines = [];
        /** @var BasketProduct $basketProduct */
        foreach ($basket as $basketProduct) {
            $lines[] = [
                'id' => $basketProduct->id(),
                'quantity' => $basketProduct->quantity(),
                'name' => $basketProduct->name(),
                'totalPrice' => $basketProduct->totalPrice(),
                'option' => $basketProduct->option() ? $basketProduct->option()->name() : null,
                'comment' => $basketProduct->comment(),
                'supplements' => array_map(
                    function (BasketProductSupplement $supplement) {
                        return $supplement->name();
                    },
                    $basketProduct->supplements()
                ),
            ];
        }

        return new BasketViewModel(
            $basket->totalPrice(),
            $basket->checkSum(),
            $lines
        );
    }

    public function count()
    {
        return count($this->products);
    }

    public function totalPrice()
    {
        return $this->totalPrice;
    }
}
