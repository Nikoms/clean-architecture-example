<?php declare(strict_types=1);

namespace Seat\Domain\Menu\Model;

class MenuLine
{
    private $name;
    private $description;
    private $options;
    private $supplements;
    private $products;

    /**
     * @param string           $name
     * @param string           $description
     * @param MenuProduct[]    $products
     * @param MenuOption[]     $options
     * @param MenuSupplement[] $supplements
     */
    public function __construct(string $name, ?string $description, array $products, array $options, array $supplements)
    {
        $this->name = $name;
        $this->description = $description;
        $this->options = $options;
        $this->supplements = $supplements;
        $this->products = $products;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * @return MenuOption[]
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * @return MenuSupplement[]
     */
    public function supplements(): array
    {
        return $this->supplements;
    }

    public function products()
    {
        return $this->products;
    }
}
