<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Model;

class OrderTypeName
{
    static $takeAway = 'take-away';
    static $delivery = 'delivery';

    static $all = [];
}

OrderTypeName::$all = [OrderTypeName::$takeAway, OrderTypeName::$delivery];

