<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Service\Error;

class TakeAwayNotAvailable extends \Exception
{
    protected $message = 'Take away not available';
}
