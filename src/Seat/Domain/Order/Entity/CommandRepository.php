<?php declare(strict_types=1);

namespace Seat\Domain\Order\Entity;

interface CommandRepository
{
    /**
     * @return Command[]
     */
    public function getTodayList(): array;

    public function add(Command $command): void;
}
