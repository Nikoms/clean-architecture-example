<?php declare(strict_types=1);

namespace Seat\Domain\Order\UseCase\ConfirmBasket;

use DateTimeImmutable;
use Exception;
use Ramsey\Uuid\Uuid;
use Seat\Domain\Client\Entity\Client;
use Seat\Domain\Client\Entity\ClientRepository;
use Seat\Domain\Basket\Entity\Basket;
use Seat\Domain\Basket\Entity\BasketRepository;
use Seat\Domain\Order\Entity\Command;
use Seat\Domain\Order\Entity\CommandRepository;
use Seat\Domain\Basket\Model\OrderType;
use Seat\Domain\Basket\Service\OrderTypeChecker;

class ConfirmBasket
{
    private $commandRepository;
    private $basketRepository;
    private $clientRepository;
    private $orderTypeChecker;

    public function __construct(
        CommandRepository $commandRepository,
        BasketRepository $basketRepository,
        ClientRepository $clientRepository,
        OrderTypeChecker $orderTypeChecker
    ) {
        $this->commandRepository = $commandRepository;
        $this->basketRepository = $basketRepository;
        $this->clientRepository = $clientRepository;
        $this->orderTypeChecker = $orderTypeChecker;
    }

    public function execute(ConfirmBasketRequest $request, ConfirmBasketPresenter $presenter)
    {
        $response = new ConfirmBasketResponse();
        $this->doExecute($request, $response);
        $presenter->present($response);
    }

    private function doExecute(ConfirmBasketRequest $request, ConfirmBasketResponse $response)
    {
        $client = $this->checkClient($request, $response);
        if ($client === null) {
            return;
        }
        $basket = $this->checkBasket($request, $response);
        if ($basket === null) {
            return;
        }
        $orderType = $this->checkOrderType($request, $response, $client);
        if ($orderType === null) {
            return;
        }
        $this->saveBasketToCommand($orderType, $basket, $client);
    }

    private function saveBasketToCommand(OrderType $orderType, Basket $basket, Client $client): void
    {
        $command = new Command(
            Uuid::uuid4()->toString(),
            $client->id(),
            $orderType,
            $basket,
            new DateTimeImmutable()
        );

        $this->commandRepository->add($command);
        $this->basketRepository->emptyBasketFor($client->id());
    }

    private function checkClient(ConfirmBasketRequest $request, ConfirmBasketResponse $response)
    {
        if ($request->userId === null) {
            $response->addError('userId', 'unknown-client');

            return null;
        }

        $client = $this->clientRepository->getClientById($request->userId);

        if ($client === null) {
            $response->addError('userId', 'unknown-client');

            return null;
        }

        return $client;
    }

    private function checkBasket(ConfirmBasketRequest $request, ConfirmBasketResponse $response): ?Basket
    {
        $basket = $this->basketRepository->getUserBasket($request->userId);

        if ($basket->isEmpty()) {
            $response->addError('userId', 'empty-basket');

            return null;
        }

        if ($request->checkSum !== $basket->checkSum()) {
            $response->addError('checkSum', 'wrong-check-sum');

            return null;
        }

        return $basket;
    }

    private function checkOrderType(ConfirmBasketRequest $request, ConfirmBasketResponse $response, Client $client): ?OrderType
    {
        try {
            $orderType = OrderType::fromString($request->orderTypeName, $request->takeAwayTime);
        } catch (Exception $e) {
            $response->addError('orderTypeName', $e->getMessage());

            return null;
        }
        try {
            $this->orderTypeChecker->checkPossibleOrderType($orderType, $client->companyId());
        } catch (Exception $e) {
            $response->addError('orderTypeName', $e->getMessage());

            return null;

        }

        return $orderType;
    }
}
