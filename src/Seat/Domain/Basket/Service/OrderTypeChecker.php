<?php declare(strict_types=1);

namespace Seat\Domain\Basket\Service;

use DateInterval;
use DateTimeZone;
use Seat\Domain\Client\Entity\ClientRepository;
use Seat\Domain\Client\Entity\CompanyRepository;
use Seat\Domain\Basket\Service\Error\DeliveryNotAvailable;
use Seat\Domain\Basket\Service\Error\OrderTooLate;
use Seat\Domain\Basket\Service\Error\TakeAwayNotAvailable;
use Seat\Domain\Basket\Service\Error\TakeAwayTooLate;
use Seat\Domain\Basket\Model\OrderType;
use Seat\Domain\Basket\Model\OrderTypeName;
use Seat\Domain\Basket\Model\PossibleOrderType;
use Seat\SharedKernel\Model\TimeRange;
use Seat\SharedKernel\Service\Clock;

class OrderTypeChecker
{
    const MAX_TAKE_AWAY_ORDER_HOUR = '11:10:00';
    const MAX_TAKE_AWAY_HOUR = '14:00:00';

    private $companyRepository;
    private $clientRepository;
    private $clock;

    public function __construct(
        CompanyRepository $companyRepository,
        ClientRepository $clientRepository,
        Clock $clock
    ) {
        $this->companyRepository = $companyRepository;
        $this->clientRepository = $clientRepository;
        $this->clock = $clock;
    }

    public function getPossibleOrderType(string $userId): PossibleOrderType
    {
        $client = $this->clientRepository->getClientById($userId);

        if ($client === null) {
            throw new \Exception('Unknown client');
        }

        return $this->getPossibleOrderTypeForCompany($client->companyId());

    }

    private function getPossibleOrderTypeForCompany(?string $companyId)
    {
        $confirmationTime = $this->clock->now()->setTimezone(new DateTimeZone('Europe/Brussels'));
        $company = null;
        if ($companyId !== null) {
            $company = $this->companyRepository->getCompanyById($companyId);
        }

        if ($company && $company->canBeDelivered()) {
            if ($confirmationTime->format('H:i:s') < $company->maxOrderTimeForDelivery()) {
                return new PossibleOrderType(OrderTypeName::$delivery, null, null);
            }

            return new PossibleOrderType(OrderTypeName::$delivery, null, new OrderTooLate());
        } else {
            if ($confirmationTime->format('H:i:s') > self::MAX_TAKE_AWAY_ORDER_HOUR) {
                return new PossibleOrderType(OrderTypeName::$takeAway, null, new OrderTooLate());
            }
            $currentTime = $confirmationTime->add(DateInterval::createFromDateString('30 minutes'));
            $timeRange = (new TimeRange('10:30:00', '14:00:00'))->delayFromDate($currentTime, 15);

            if (count($timeRange->getRoundedStep(15)) === 0) {
                return new PossibleOrderType(OrderTypeName::$takeAway, null, new TakeAwayTooLate());
            }

            return new PossibleOrderType(OrderTypeName::$takeAway, $timeRange, null);
        }
    }

    /**
     * @throws DeliveryNotAvailable
     * @throws OrderTooLate
     * @throws TakeAwayTooLate
     * @throws TakeAwayNotAvailable
     */
    public function checkPossibleOrderType(OrderType $orderType, ?string $companyId)
    {
        $possibleOrderType = $this->getPossibleOrderTypeForCompany($companyId);

        if ($orderType->isDelivery() && $possibleOrderType->name() === OrderTypeName::$takeAway) {
            throw new DeliveryNotAvailable();
        }
        if ($orderType->isTakeAway() && $possibleOrderType->name() === OrderTypeName::$delivery) {
            throw new TakeAwayNotAvailable();
        }
        if ($possibleOrderType->error()) {
            throw $possibleOrderType->error();
        }

        if ($orderType->isTakeAway() && $orderType->time() > $possibleOrderType->range()->to()) {
            throw new TakeAwayTooLate();
        }
    }
}
