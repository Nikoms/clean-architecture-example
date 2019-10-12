<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Model;

class BasketProduct
{
    private $name;
    private $price;
    private $option;
    /** @var BasketProductSupplement[] */
    private $supplements;
    private $comment;
    private $quantity;
    private $id;

    public function __construct(
        string $id,
        int $quantity,
        string $name,
        float $price,
        ?BasketProductOption $option,
        array $supplements,
        string $comment
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->option = $option;
        $this->supplements = $supplements;
        $this->comment = $comment;
        $this->quantity = $quantity;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function option(): ?BasketProductOption
    {
        return $this->option;
    }

    /**
     * @return BasketProductSupplement[]
     */
    public function supplements(): array
    {
        return $this->supplements;
    }

    public function comment(): string
    {
        return $this->comment;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function totalPrice()
    {
        $productPrice = $this->price();
        if ($this->option()) {
            $productPrice += $this->option()->price();
        }
        foreach ($this->supplements() as $supplement) {
            $productPrice += $supplement->price();
        }

        return $productPrice * $this->quantity();
    }
}
