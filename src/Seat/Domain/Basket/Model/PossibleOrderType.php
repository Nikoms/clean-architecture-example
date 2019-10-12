<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Model;

use Seat\SharedKernel\Model\TimeRange;

class PossibleOrderType
{
    private $name;
    private $range;
    private $error;

    public function __construct(string $name, ?TimeRange $range, ?\Exception $error)
    {
        $this->name = $name;
        $this->range = $range;
        $this->error = $error;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function range(): ?TimeRange
    {
        return $this->range;
    }

    public function error(): ?\Exception
    {
        return $this->error;
    }
}
