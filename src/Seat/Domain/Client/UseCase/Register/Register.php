<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\Register;

use Assert\Assert;
use Assert\LazyAssertionException;
use Seat\Domain\Client\Entity\Client;
use Seat\Domain\Client\Entity\ClientRepository;
use Seat\Domain\Client\Entity\Company;
use Seat\Domain\Client\Entity\CompanyRepository;
use Seat\Domain\Client\Entity\Role;
use Seat\Domain\Client\Entity\Store;
use Seat\SharedKernel\Service\IdGenerator;
use Seat\SharedKernel\Service\PasswordHasher;

class Register
{
    private $idGenerator;
    private $clientRepository;
    private $passwordHasher;
    private $companyRepository;

    public function __construct(
        IdGenerator $idGenerator,
        ClientRepository $clientRepository,
        CompanyRepository $companyRepository,
        PasswordHasher $passwordHasher
    ) {
        $this->clientRepository = $clientRepository;
        $this->idGenerator = $idGenerator;
        $this->passwordHasher = $passwordHasher;
        $this->companyRepository = $companyRepository;
    }

    public function execute(RegisterRequest $request): void
    {
        $response = new RegisterResponse();

        if ($request->isPosted) {
            $this->register($request, $response);
        }
        $presenter->present($response);
    }

    private function register(RegisterRequest $request, RegisterResponse $response)
    {
        $isValid = $this->validateRequest($request, $response);
        $isValid = $this->validateCompany($request, $response, $company) && $isValid;
        $isValid = $this->validateClient($request, $response) && $isValid;
        if ($isValid) {
            $this->saveClient($request, $response, $company);
        }
    }

    private function validateClient(RegisterRequest $request, RegisterResponse $response): bool
    {
        if ($request->email === null) {
            return false;
        }
        $existingClient = $this->clientRepository->getClientByEmail((string)$request->email);
        if ($existingClient !== null) {
            $response->addError('email', 'email-already-used');

            return false;
        }

        return true;
    }

    private function validateCompany(RegisterRequest $request, RegisterResponse $response, &$company): bool
    {
        $company = null;
        if ($request->companyName !== null) {
            $company = $this->companyRepository->getCompanyNamed($request->companyName);
            if ($company === null) {
                $response->addError('companyName', 'unknown-company');

                return false;
            }
            if (!$company->isEnabled()) {
                $response->addError('companyName', 'disabled-company');

                return false;
            }
        }

        return true;
    }

    private function saveClient(RegisterRequest $request, RegisterResponse $response, ?Company $company): void
    {
        $hashedPassword = $this->passwordHasher->hash($request->password);
        $client = new Client(
            $this->idGenerator->next(),
            $request->firstName,
            $request->lastName,
            $request->email,
            $request->phoneNumber,
            $hashedPassword,
            $company ? $company->store() : $request->store,
            Role::$client,
            true,
            $company ? $company->id() : null
        );
        $this->clientRepository->addClient($client);
        $response->setRegisteredClient($client);
    }

    private function validateRequest(RegisterRequest $request, RegisterResponse $response)
    {
        try {
            Assert::lazy()
                ->that($request->firstName, 'firstName')->notEmpty('error-notEmpty')->string('error-string')
                ->that($request->lastName, 'lastName')->notEmpty('error-notEmpty')->string('error-string')
                ->that($request->email, 'email')->notEmpty('error-notEmpty')->email('invalid-email')
                ->that($request->phoneNumber, 'phoneNumber')->notEmpty('error-notEmpty')
                ->that($request->password, 'password')->notEmpty('error-notEmpty')->string('error-string')
                ->that($request->companyName, 'companyName')
                ->that($request->store, 'store')->notEmpty('error-notEmpty')->choice(Store::$all, 'error-choice')
                ->verifyNow();

            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }

            return false;
        }
    }

}
