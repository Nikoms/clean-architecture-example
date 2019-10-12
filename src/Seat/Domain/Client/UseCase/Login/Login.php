<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\Login;

use Seat\Domain\Client\Entity\Client;
use Seat\Domain\Client\Entity\ClientRepository;
use Seat\Domain\Client\Entity\CompanyRepository;
use Seat\SharedKernel\Service\PasswordHasher;

class Login
{
    private $clientRepository;
    private $passwordHasher;
    private $companyRepository;

    public function __construct(ClientRepository $clientRepository, CompanyRepository $companyRepository, PasswordHasher $passwordHasher)
    {
        $this->clientRepository = $clientRepository;
        $this->passwordHasher = $passwordHasher;
        $this->companyRepository = $companyRepository;
    }

    /**
     * throws DisabledUser
     * throws DisabledCompany
     * throws UnknownUser
     * throws WrongPassword
     */
    public function execute(LoginRequest $request, LoginPresenter $presenter)
    {
        $response = new LoginResponse();
        $client = $this->clientRepository->getClientByEmail($request->email);

        $isValid = $this->checkClient($client, $response);
        $isValid = $isValid && $this->checkPassword($request, $client, $response);
        $isValid = $isValid && $this->checkClientEnabled($client, $response);
        $isValid = $isValid && $this->checkCompanyEnabled($client, $response);

        if($isValid){
            $response->setClient($client);
        }

        return $presenter->present($response);
    }

    private function checkClient(?Client $client, LoginResponse $response): bool
    {
        if ($client === null) {
            $response->addError('email', 'unknown-email');

            return false;
        }

        return true;
    }

    private function checkPassword(LoginRequest $request, Client $client, LoginResponse $response): bool
    {
        if (!$this->passwordHasher->isPasswordValid($client->password(), $request->password)) {
            $response->addError('password', 'invalid-password');

            return false;
        }

        return true;
    }

    private function checkClientEnabled(Client $client, LoginResponse $response): bool
    {
        if (!$client->isEnabled()) {
            $response->addError('email', 'disabled-user');

            return false;
        }

        return true;
    }

    private function checkCompanyEnabled(Client $client, LoginResponse $response): bool
    {
        if ($client->hasCompany()) {
            $company = $this->companyRepository->getCompanyById($client->companyId());
            if (!$company->isEnabled()) {
                $response->addError('email', 'disabled-company');

                return false;
            }
        }

        return true;
    }
}
