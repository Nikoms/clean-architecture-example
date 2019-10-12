<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Seat\Domain\Basket\Model\BasketProduct;
use Seat\Domain\Basket\Model\BasketProductOption;
use Seat\Domain\Basket\Model\BasketProductSupplement;

class BasketProductJson
{
    private $json = [];

    public static function fromBasketProduct(BasketProduct $basketProduct)
    {
        $json = [
            'id' => $basketProduct->id(),
            'quantity' => $basketProduct->quantity(),
            'comment' => $basketProduct->comment(),
            'name' => $basketProduct->name(),
            'price' => $basketProduct->price(),
        ];

        if ($basketProduct->option()) {
            $json['option'] = [
                'name' => $basketProduct->option()->name(),
                'price' => $basketProduct->option()->price(),
            ];
        }

        if (count($basketProduct->supplements()) > 0) {
            $supplementToJson = function (BasketProductSupplement $supplement) {
                return [
                    'name' => $supplement->name(),
                    'price' => $supplement->price(),
                ];
            };
            $json['supplements'] = array_map($supplementToJson, $basketProduct->supplements());
        }

        return new BasketProductJson($json);
    }

    public function __construct(array $json)
    {
        $this->json = $json;
    }

    public function json()
    {
        return $this->json;
    }

    public function basketProduct(string $basketId)
    {
        $json = $this->json;
        $supplementsToObjects = function (array $supplement) {
            return new BasketProductSupplement($supplement['name'], $supplement['price']);
        };

        return new BasketProduct(
            $basketId,
            $json['quantity'],
            $json['name'],
            $json['price'],
            isset($json['option']) ? new BasketProductOption($json['option']['name'], $json['option']['price']) : null,
            isset($json['supplements']) ? array_map($supplementsToObjects, $json['supplements']) : [],
            $json['comment']
        );
    }
}
