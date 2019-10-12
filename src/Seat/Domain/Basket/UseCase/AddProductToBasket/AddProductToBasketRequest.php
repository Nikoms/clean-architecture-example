<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\AddProductToBasket;

class AddProductToBasketRequest
{
    /** @var string */
    public $userId;
    /** @var string */
    public $productId;
    /** @var string|int */
    public $optionId;
    /** @var array */
    public $supplementIds;
    /** @var string */
    public $comment;
    /** @var int */
    public $quantity;

    public static function fromAll($quantity, $userId, $productId, $optionId = null, $supplementIds = [], $comment = '')
    {
        $request = new self();
        $request->quantity = (int)$quantity;
        $request->userId = $userId;
        $request->productId = $productId;
        $request->optionId = $optionId;
        $request->supplementIds = $supplementIds;
        $request->comment = $comment;

        return $request;
    }

    public function withUserId(string $userId)
    {
        $this->userId = $userId;

        return $this;
    }
}
