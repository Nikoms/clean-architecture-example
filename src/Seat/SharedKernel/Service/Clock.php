<?php declare(strict_types=1);

namespace Seat\SharedKernel\Service;

class Clock
{
    public function now()
    {
        return new \DateTimeImmutable();
    }
}
