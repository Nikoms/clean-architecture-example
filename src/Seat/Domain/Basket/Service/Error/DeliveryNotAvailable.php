<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Service\Error;

class DeliveryNotAvailable extends \Exception
{
    protected $message = 'Delivery not available';
}
