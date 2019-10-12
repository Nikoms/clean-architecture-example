<?php declare(strict_types=1);

namespace Seat\Presentation\Basket;

class AddProductToBasketHtmlViewModel
{
    public $notifications = [];

    public function addNotification(string $type, string $message)
    {
        $this->notifications[] = ['type' => $type, 'message' => $message];
    }
}
