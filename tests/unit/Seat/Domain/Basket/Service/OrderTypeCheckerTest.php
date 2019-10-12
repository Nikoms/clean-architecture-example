<?php declare(strict_types=1);

namespace SeatTest\Domain\Basket\Service;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Seat\Domain\Basket\Model\OrderType;
use Seat\Domain\Basket\Service\OrderTypeChecker;
use Seat\SharedKernel\Service\Clock;
use SeatTest\_Mock\Domain\Client\Entity\InMemoryClientRepository;
use SeatTest\_Mock\Domain\Client\Entity\InMemoryCompanyRepository;
use SeatTest\Domain\Client\Entity\CompanyBuilder;


class OrderTypeCheckerTest extends TestCase
{
    private $checker;
    private $companyRepository;
    private $clientRepository;

    private $company;
    private $companyWithoutDelivery;
    /**
     *
     */
    private $clock;

    protected function setUp()
    {
        $this->companyRepository = new InMemoryCompanyRepository();
        $this->clientRepository = new InMemoryClientRepository();
        $this->clock = $this->createMock(Clock::class);
        $this->checker = new OrderTypeChecker($this->companyRepository, $this->clientRepository, $this->clock);

        $this->company = CompanyBuilder::aCompany()->deliverable('10:00:00')->build();
        $this->companyWithoutDelivery = CompanyBuilder::aCompany()->build();
        $this->companyRepository->addCompany($this->company);
        $this->companyRepository->addCompany($this->companyWithoutDelivery);
    }

    private function nowIs(string $hourMinuteSecond)
    {
        $this->clock->method('now')->willReturn(
            DateTimeImmutable::createFromFormat('H:i:s', $hourMinuteSecond, new \DateTimeZone('Europe/Brussels'))
        );

        return '';
    }

    public function test_order_delivery_must_be_before_the_company_max_order_time()
    {
        $this->nowIs('09:59:59');

        $this->assertNull(
            $this->checker->checkPossibleOrderType(OrderType::delivery(), $this->company->id())
        );
    }

    public function test_take_away_must_be_ordered_before_11h10()
    {
        $this->nowIs('11:10:00');

        $this->assertNull(
            $this->checker->checkPossibleOrderType(OrderType::takeAwayFor('00:00:00'), null)
        );
    }

    public function test_take_away_is_possible_until_14h()
    {
        $this->nowIs('11:10:00');
        $this->assertNull(
            $this->checker->checkPossibleOrderType(OrderType::takeAwayFor('14:00:00'), null)
        );
    }

    /**
     * @expectedException \Seat\Domain\Basket\Service\Error\DeliveryNotAvailable
     */
    public function test_a_user_without_a_company_can_not_choose_delivery()
    {
        $this->nowIs('09:00:00');
        $this->checker->checkPossibleOrderType(OrderType::delivery(), null);
    }

    /**
     * @expectedException \Seat\Domain\Basket\Service\Error\DeliveryNotAvailable
     */
    public function test_impossible_to_delivery_on_a_company_that_does_not_accept_delivery()
    {
        $this->nowIs('09:00:00');
        $this->checker->checkPossibleOrderType(OrderType::delivery(), $this->companyWithoutDelivery->id());
    }

    /**
     * @expectedException \Seat\Domain\Basket\Service\Error\OrderTooLate
     */
    public function test_take_away_can_not_be_ordered_after_11h10()
    {
        $this->nowIs('11:10:01');
        $this->checker->checkPossibleOrderType(OrderType::takeAwayFor('00:00:00'), null);
    }

    /**
     * @expectedException \Seat\Domain\Basket\Service\Error\OrderTooLate
     */
    public function test_impossible_to_order_a_delivery_after_the_company_max_order_time()
    {
        $this->nowIs('10:00:01');
        $this->checker->checkPossibleOrderType(OrderType::delivery(), $this->company->id());
    }

    /**
     * @expectedException \Seat\Domain\Basket\Service\Error\TakeAwayTooLate
     */
    public function test_impossible_to_order_a_take_away_for_14h()
    {
        $this->nowIs('10:00:01');
        $this->checker->checkPossibleOrderType(OrderType::takeAwayFor('14:00:01'), $this->companyWithoutDelivery->id());
    }

    /**
     * @expectedException \Seat\Domain\Basket\Service\Error\TakeAwayNotAvailable
     */
    public function test_a_daliverable_company_can_not_take_out()
    {
        $this->nowIs('10:00:00');
        $this->checker->checkPossibleOrderType(OrderType::takeAwayFor('12:00:00'), $this->company->id());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Unknown client
     */
    public function test_an_unknown_client_has_no_delivery()
    {
        $this->nowIs('10:00:00');
        $this->assertNull($this->checker->getPossibleOrderType('unknown-client'));
    }
}
