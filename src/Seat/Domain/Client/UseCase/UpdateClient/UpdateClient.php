<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\UpdateClient;

use Assert\Assert;
use Assert\LazyAssertionException;
use Seat\Domain\Client\Entity\Client;
use Seat\Domain\Client\Entity\ClientRepository;
use Seat\SharedKernel\Service\PasswordHasher;

class UpdateClient
{
    private $clientRepository;
    private $passwordHasher;

    public function __construct(ClientRepository $clientRepository, PasswordHasher $passwordHasher)
    {
        $this->clientRepository = $clientRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute(UpdateClientRequest $request, UpdateClientPresenter $presenter)
    {
        $response = new UpdateClientResponse();
        $response->setRequest($request);

        /** @var Client $client */
        $isValid = $this->validateClient($request, $response, $client);
        $isValid = $this->validateRequest($request, $response) && $isValid;

        if ($isValid) {
            $client = new Client(
                $client->id(),
                $request->firstName,
                $request->lastName,
                $request->email,
                $request->phoneNumber,
                $request->password ? $this->passwordHasher->hash($request->password) : $client->password(),
                $client->store(),
                $client->role(),
                $client->isEnabled(),
                $client->companyId()
            );
            $this->clientRepository->updateClient($client);
            $response->setUpdatedClient($client);
        }

        $presenter->present($response);
    }

    private function validateRequest(UpdateClientRequest $request, UpdateClientResponse $response)
    {
        try {
            Assert::lazy()
                ->that($request->clientId, 'clientId')->notEmpty('error-notEmpty')->string('string')
                ->that($request->firstName, 'firstName')->notEmpty('error-notEmpty')->string('string')
                ->that($request->lastName, 'lastName')->notEmpty('error-notEmpty')->string('string')
                ->that($request->email, 'email')->notEmpty('error-notEmpty')->email('invalid-email')
                ->that($request->phoneNumber, 'phoneNumber')->notEmpty('error-notEmpty')
                ->that($request->password, 'password')->nullOr()->string('string')
                ->verifyNow();

            return true;

        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }

            return false;
        }
    }

    private function validateClient(UpdateClientRequest $request, UpdateClientResponse $response, ?Client &$client)
    {
        if ($request->clientId === null) {
            return false;
        }

        $client = $this->clientRepository->getClientById($request->clientId);

        if ($client === null) {
            $response->addError('clientId', 'unknown-client');

            return false;
        }

        $response->setOriginalClient($client);

        return true;
    }
}
