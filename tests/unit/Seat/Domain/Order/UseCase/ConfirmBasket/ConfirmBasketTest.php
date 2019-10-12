<?php declare(strict_types=1);

namespace SeatTest\Domain\Order\UseCase\ConfirmBasket;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Seat\Domain\Basket\Entity\Basket;
use Seat\Domain\Basket\Model\BasketProduct;
use Seat\Domain\Basket\Service\OrderTypeChecker;
use Seat\Domain\Order\UseCase\ConfirmBasket\ConfirmBasket;
use Seat\Domain\Order\UseCase\ConfirmBasket\ConfirmBasketPresenter;
use Seat\Domain\Order\UseCase\ConfirmBasket\ConfirmBasketResponse;
use Seat\SharedKernel\Error\Notification;
use Seat\SharedKernel\Service\Clock;
use SeatTest\_Mock\Domain\Client\Entity\InMemoryClientRepository;
use SeatTest\_Mock\Domain\Client\Entity\InMemoryCompanyRepository;
use SeatTest\_Mock\Domain\Basket\Entity\InMemoryBasketRepository;
use SeatTest\_Mock\Domain\Order\Entity\InMemoryCommandRepository;
use SeatTest\Domain\Client\Entity\ClientBuilder;
use SeatTest\Domain\Client\Entity\CompanyBuilder;

class ConfirmBasketTest extends TestCase implements ConfirmBasketPresenter
{
    private $commandRepository;
    private $basketRepository;
    private $clientRepository;

    private $company;

    private $takeAwayClient;
    private $deliveredClient;
    private $clientWithoutBasket;

    private $confirmBasket;
    private $basketProduct;
    private $basket;
    /** @var ConfirmBasketResponse */
    private $response;
    private $companyRepository;
    private $clock;

    protected function setUp()
    {
        $this->commandRepository = new InMemoryCommandRepository();
        $this->basketRepository = new InMemoryBasketRepository();
        $this->clientRepository = new InMemoryClientRepository();
        $this->companyRepository = new InMemoryCompanyRepository();

        $this->company = CompanyBuilder::aCompany()->deliverable('11:00:00')->build();

        $this->deliveredClient = ClientBuilder::aClient()->withCompanyId($this->company->id())->build();
        $this->takeAwayClient = ClientBuilder::aClient()->build();
        $this->clientWithoutBasket = ClientBuilder::aClient()->withCompanyId($this->company->id())->build();
        $this->clientRepository->addClient($this->deliveredClient);
        $this->clientRepository->addClient($this->takeAwayClient);
        $this->clientRepository->addClient($this->clientWithoutBasket);
        $this->companyRepository->addCompany($this->company);

        $this->basketProduct = new BasketProduct('product-id', 1, 'Product 1', 2, null, [], '');
        $this->basket = new Basket('_not_used_', [$this->basketProduct]);
        $this->basketRepository->addToBasket($this->deliveredClient->id(), $this->basketProduct);
        $this->basketRepository->addToBasket($this->takeAwayClient->id(), $this->basketProduct);

        $this->clock = $this->createMock(Clock::class);
        $this->confirmBasket = new ConfirmBasket(
            $this->commandRepository,
            $this->basketRepository,
            $this->clientRepository,
            new OrderTypeChecker($this->companyRepository, $this->clientRepository, $this->clock)
        );
    }

    public function present(ConfirmBasketResponse $response): void
    {
        $this->response = $response;
    }

    private function nowIs(string $hourMinute)
    {
        $this->clock->method('now')->willReturn(
            DateTimeImmutable::createFromFormat('H:i', $hourMinute, new \DateTimeZone('Europe/Brussels'))
        );
    }

    public function test_it_saves_take_away_into_a_command()
    {
        $this->nowIs('10:00');

        $request = ConfirmBasketRequestBuilder::aConfirmBasket()
            ->takeAwayAt('10:00')
            ->forBasket($this->basket)
            ->forClient($this->takeAwayClient)
            ->build();

        $this->confirmBasket->execute($request, $this);

        $commands = $this->commandRepository->getTodayList();

        $this->assertCount(1, $commands);
        $this->assertSame('take-away', $commands[0]->orderType()->name());
        $this->assertSame($this->takeAwayClient->id(), $commands[0]->userId());
        $this->assertSame($this->basket->totalPrice(), $commands[0]->basket()->totalPrice());
    }

    public function test_it_saves_delivery_into_a_command()
    {
        $this->nowIs('10:00');
        $request = ConfirmBasketRequestBuilder::aConfirmBasket()->forBasket($this->basket)->forClient($this->deliveredClient)->build();

        $this->confirmBasket->execute($request, $this);

        $commands = $this->commandRepository->getTodayList();

        $this->assertCount(1, $commands);
        $this->assertSame('delivery', $commands[0]->orderType()->name());
        $this->assertSame($this->deliveredClient->id(), $commands[0]->userId());
    }

    public function test_a_delivery_company_can_not_take_away()
    {
        $this->nowIs('10:00');

        $request = ConfirmBasketRequestBuilder::aConfirmBasket()
            ->takeAwayAt('10:00')
            ->forBasket($this->basket)
            ->forClient($this->deliveredClient)
            ->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertEquals(
            (new Notification())->addError('orderTypeName', 'Take away not available'),
            $this->response->notification()
        );
    }

    public function test_a_take_away_client_can_not_deliver()
    {
        $this->nowIs('10:00');

        $request = ConfirmBasketRequestBuilder::aConfirmBasket()
            ->forBasket($this->basket)
            ->forClient($this->takeAwayClient)
            ->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertEquals(
            (new Notification())->addError('orderTypeName', 'Delivery not available'),
            $this->response->notification()
        );
    }

    public function test_it_fails_when_the_given_check_sum_is_not_correct()
    {
        $this->nowIs('10:00');
        $request = ConfirmBasketRequestBuilder::aConfirmBasket()->withCheckSum('wrong')->forClient($this->deliveredClient)->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertEquals(
            (new Notification())->addError('checkSum', 'wrong-check-sum'),
            $this->response->notification()
        );
    }

    public function test_an_empty_basket_can_not_be_confirmed()
    {
        $this->nowIs('10:00');
        $request = ConfirmBasketRequestBuilder::aConfirmBasket()->forClient($this->clientWithoutBasket)->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertEquals(
            (new Notification())->addError('userId', 'empty-basket'),
            $this->response->notification()
        );
    }

    public function test_it_empties_the_basket()
    {
        $this->nowIs('10:00');
        $request = ConfirmBasketRequestBuilder::aConfirmBasket()
            ->forBasket($this->basket)
            ->forClient($this->takeAwayClient)
            ->takeAwayAt('10:00')
            ->build();

        $this->confirmBasket->execute($request, $this);

        $basket = $this->basketRepository->getUserBasket($this->takeAwayClient->id());
        $this->assertTrue($basket->isEmpty());
    }

    public function test_throws_an_error_when_the_client_is_unknown()
    {
        $this->nowIs('10:00');
        $request = ConfirmBasketRequestBuilder::aConfirmBasket()->withUserId('unknown client')->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertEquals(
            (new Notification())->addError('userId', 'unknown-client'),
            $this->response->notification()
        );
    }

    public function test_a_deliver_must_be_done_before_company_limit_time()
    {
        $this->nowIs('11:30');

        $request = ConfirmBasketRequestBuilder::aConfirmBasket()
            ->forBasket($this->basket)
            ->forClient($this->deliveredClient)
            ->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertEquals(
            (new Notification())->addError('orderTypeName', 'Order too late'),
            $this->response->notification()
        );
    }

    public function test_a_take_away_done_at_11h11_is_too_late()
    {
        $this->nowIs('11:11');

        $request = ConfirmBasketRequestBuilder::aConfirmBasket()
            ->forBasket($this->basket)
            ->forClient($this->takeAwayClient)
            ->takeAwayAt('14:00')
            ->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertEquals(
            (new Notification())->addError('orderTypeName', 'Order too late'),
            $this->response->notification()
        );
    }

    public function test_a_take_away_time_14h01_is_too_late()
    {
        $this->nowIs('11:10');

        $request = ConfirmBasketRequestBuilder::aConfirmBasket()
            ->forBasket($this->basket)
            ->forClient($this->takeAwayClient)
            ->takeAwayAt('14:01')
            ->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertEquals(
            (new Notification())->addError('orderTypeName', 'Take away too late'),
            $this->response->notification()
        );
    }

    public function test_a_take_away_time_can_be_set_to_maximum_14h00()
    {
        $this->nowIs('11:10');

        $request = ConfirmBasketRequestBuilder::aConfirmBasket()
            ->forBasket($this->basket)
            ->forClient($this->takeAwayClient)
            ->takeAwayAt('14:00')
            ->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertCount(1, $this->commandRepository->getTodayList());
    }

    public function test_it_notifies_when_the_type_is_unknown()
    {
        $request = ConfirmBasketRequestBuilder::aConfirmBasket()
            ->forBasket($this->basket)
            ->forClient($this->deliveredClient)
            ->withOrderTypeName('fake order type')
            ->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertEquals(
            (new Notification())->addError('orderTypeName', 'Invalid order type'),
            $this->response->notification()
        );
    }

    public function test_a_null_client_is_considered_unknown()
    {
        $request = ConfirmBasketRequestBuilder::aConfirmBasket()
            ->withUserId(null)
            ->build();

        $this->confirmBasket->execute($request, $this);

        $this->assertEquals(
            (new Notification())->addError('userId', 'unknown-client'),
            $this->response->notification()
        );
    }
}
