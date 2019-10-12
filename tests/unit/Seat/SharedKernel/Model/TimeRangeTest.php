<?php declare(strict_types=1);

namespace SeatTest\SharedKernel\Model;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Seat\SharedKernel\Model\TimeRange;

class TimeRangeTest extends TestCase
{

    public function test_returns_steps()
    {
        $timeRange = new TimeRange('10:00:00', '11:00:00');

        $this->assertSame(['10:00:00', '10:15:00', '10:30:00', '10:45:00', '11:00:00'], $timeRange->getRoundedStep(15));
    }

    public function test_from_can_be_delayed()
    {
        $timeRange = new TimeRange('10:00:00', '11:00:00');
        $delay = DateTimeImmutable::createFromFormat('H:i:s', '10:26:21');
        $timeRange = $timeRange->delayFromDate($delay);

        $this->assertSame(['10:26:21', '10:41:21', '10:56:21',], $timeRange->getRoundedStep(15));
    }

    public function test_from_can_be_delayed_and_rounded()
    {
        $timeRange = new TimeRange('10:00:00', '11:00:00');
        $delay = DateTimeImmutable::createFromFormat('H:i:s', '10:20:21');
        $timeRange = $timeRange->delayFromDate($delay, 10);

        $this->assertSame(['10:30:00', '10:45:00', '11:00:00'], $timeRange->getRoundedStep(15));
    }

    public function test_can_not_delay_before_the_current_from_time()
    {
        $timeRange = new TimeRange('10:00:00', '11:00:00');
        $delay = DateTimeImmutable::createFromFormat('H:i:s', '09:30:00');
        $timeRange = $timeRange->delayFromDate($delay, 10);

        $this->assertSame(['10:00:00', '10:15:00', '10:30:00', '10:45:00', '11:00:00'], $timeRange->getRoundedStep(15));
    }
}
