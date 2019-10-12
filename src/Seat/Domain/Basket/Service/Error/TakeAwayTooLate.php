<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Service\Error;

class TakeAwayTooLate extends \Exception
{
    protected $message = 'Take away too late';
}
