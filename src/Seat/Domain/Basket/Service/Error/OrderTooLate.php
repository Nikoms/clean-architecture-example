<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Service\Error;

class OrderTooLate extends \Exception
{
    protected $message = 'Order too late';
}
