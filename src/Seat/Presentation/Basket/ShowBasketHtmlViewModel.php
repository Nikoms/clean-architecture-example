<?php declare(strict_types=1);

namespace Seat\Presentation\Basket;

use Seat\Presentation\Basket\Model\BasketViewModel;

class ShowBasketHtmlViewModel
{
    /** @var BasketViewModel */
    public $basket;
    public $mustRedirectToMenu = false;
}
