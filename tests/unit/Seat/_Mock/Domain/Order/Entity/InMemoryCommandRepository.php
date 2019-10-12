<?php declare(strict_types=1);

namespace SeatTest\_Mock\Domain\Order\Entity;


use Seat\Domain\Order\Entity\Command;
use Seat\Domain\Order\Entity\CommandRepository;

class InMemoryCommandRepository implements CommandRepository
{
    /** @var Command[] */
    private $commands = [];

    public function add(Command $command): void
    {
        $this->commands[] = $command;
    }

    /**
     * @return Command[]
     */
    public function getTodayList(): array
    {
        $today = date('Y-m-d');

        return array_filter(
            $this->commands,
            function (Command $command) use ($today) {
                return $command->date()->format('Y-m-d') === $today;
            }
        );
    }
}
