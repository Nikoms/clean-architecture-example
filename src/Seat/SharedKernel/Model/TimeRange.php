<?php declare(strict_types=1);

namespace Seat\SharedKernel\Model;

use DateInterval;
use DateTimeImmutable;

class TimeRange
{
    private $from;
    private $to;

    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function getRoundedStep(int $minuteStep)
    {
        $interval = DateInterval::createFromDateString($minuteStep.' minutes');
        $current = DateTimeImmutable::createFromFormat('H:i:s', $this->from);

        $steps = [];
        do {
            $steps[] = $current->format('H:i:s');

            $current = $current->add($interval);
        } while ($current->format('H:i:s') <= $this->to);


        return $steps;
    }

    public function delayFromDate(DateTimeImmutable $delay, ?int $minuteStep = null)
    {
        $newFrom = $delay->format('H:i:s');

        if ($newFrom < $this->from) {
            return $this;
        }

        if ($minuteStep === null) {
            return new TimeRange($newFrom, $this->to);
        }

        $diffWithNextGoodMinute = $delay->getTimestamp() % ($minuteStep * 60);
        $newFromTimeStamp = ($diffWithNextGoodMinute === 0)
            ? $delay->getTimestamp()
            : $delay->getTimestamp() - $diffWithNextGoodMinute + ($minuteStep * 60);


        $newFrom = (new DateTimeImmutable())->setTimestamp($newFromTimeStamp);

        return new TimeRange($newFrom->format('H:i:s'), $this->to);
    }

    public function from(): string
    {
        return $this->from;
    }

    public function to(): string
    {
        return $this->to;
    }
}

