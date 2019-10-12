<?php declare(strict_types=1);

namespace Seat\Presentation\Menu\Model;

use Seat\Domain\Menu\Model\MenuLine;
use Seat\Domain\Menu\Model\MenuOption;
use Seat\Domain\Menu\Model\MenuProduct;
use Seat\Domain\Menu\Model\MenuSupplement;

class MenuViewModel
{
    private $categories;

    /** @param MenuLine[] $menuLines */
    public static function fromMenuLines(array $menuLines)
    {
        $categories = [];
        foreach ($menuLines as $menuLine) {
            $categories[$menuLine->name()] = [
                'description' => $menuLine->description(),
                'products' => array_map(
                    function (MenuProduct $product) {
                        return [
                            'value' => $product->id(),
                            'name' => $product->name(),
                            'description' => $product->description(),
                            'price' => $product->price(),
                        ];
                    },
                    $menuLine->products()
                ),
                'options' => array_map(
                    function (MenuOption $option) {
                        return [
                            'value' => $option->id(),
                            'text' => $option->name().' ('.$option->price().' €)',
                        ];
                    },
                    $menuLine->options()
                ),
                'supplements' => array_map(
                    function (MenuSupplement $supplement) {
                        return [
                            'value' => $supplement->id(),
                            'text' => $supplement->name().' ('.$supplement->price().' €)',
                        ];
                    },
                    $menuLine->supplements()
                ),
            ];
        }

        return new MenuViewModel($categories);
    }

    public function __construct(array $categories)
    {
        $this->categories = $categories;
    }

    public function categories(): array
    {
        return $this->categories;
    }

    public function json()
    {
        return json_encode($this->categories);
    }
}
