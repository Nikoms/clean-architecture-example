<?php declare(strict_types=1);

namespace SeatTest\Domain\Client\UseCase\Login;

use PHPUnit\Framework\TestCase;
use Seat\Domain\Client\UseCase\Login\Login;
use Seat\Domain\Client\UseCase\Login\LoginPresenter;
use Seat\Domain\Client\UseCase\Login\LoginRequest;
use Seat\Domain\Client\UseCase\Login\LoginResponse;
use Seat\SharedKernel\Error\Notification;
use Seat\SharedKernel\Service\Base64PasswordHasher;
use SeatTest\_Mock\Domain\Client\Entity\InMemoryClientRepository;
use SeatTest\_Mock\Domain\Client\Entity\InMemoryCompanyRepository;
use SeatTest\Domain\Client\Entity\ClientBuilder;
use SeatTest\Domain\Client\Entity\CompanyBuilder;

class LoginTest extends TestCase implements LoginPresenter
{
    const PASSWORD = 'my password';

    private $registeredClient;
    private $clientRepository;
    private $companyRepository;

    private $login;
    private $passwordHasher;
    /** @var LoginResponse */
    private $response;

    protected function setUp()
    {
        $this->clientRepository = new InMemoryClientRepository();
        $this->companyRepository = new InMemoryCompanyRepository();
        $this->passwordHasher = new Base64PasswordHasher();
        $this->login = new Login($this->clientRepository, $this->companyRepository, $this->passwordHasher);

        $this->registeredClient = ClientBuilder::aClient()->withPassword($this->passwordHasher->hash(self::PASSWORD))->build();
    }

    public function present(LoginResponse $response)
    {
        $this->response = $response;
    }

    public function test_it_saves_the_logged_client_in_the_response()
    {
        $this->clientRepository->addClient($this->registeredClient);

        $this->login->execute(new LoginRequest($this->registeredClient->email(), self::PASSWORD), $this);

        $this->assertNotNull($this->response->client());
        $this->assertSame($this->registeredClient->email(), $this->response->client()->email());
        $this->assertSame($this->registeredClient->firstName(), $this->response->client()->firstName());
    }

    public function test_an_error_when_the_password_is_wrong()
    {
        $this->clientRepository->addClient($this->registeredClient);

        $this->login->execute(new LoginRequest($this->registeredClient->email(), 'wrong password'), $this);
        $this->assertEquals((new Notification())->addError('password', 'invalid-password'), $this->response->notification());
    }

    public function test_throws_an_error_when_user_does_not_exist()
    {
        $this->login->execute(new LoginRequest('unknown user', 'wrong password'), $this);
        $this->assertEquals((new Notification())->addError('email', 'unknown-email'), $this->response->notification());
    }

    public function test_throws_an_error_when_the_user_is_disabled()
    {
        $client = ClientBuilder::aClient()->withPassword($this->passwordHasher->hash(self::PASSWORD))->withDisabled()->build();
        $this->clientRepository->addClient($client);

        $this->login->execute(new LoginRequest($client->email(), self::PASSWORD), $this);

        $this->assertEquals((new Notification())->addError('email', 'disabled-user'), $this->response->notification());
    }

    public function test_throws_an_error_when_his_company_is_disabled()
    {
        $company = CompanyBuilder::aCompany()->disabled()->build();
        $this->companyRepository->addCompany($company);
        $client = ClientBuilder::aClient()->withPassword($this->passwordHasher->hash(self::PASSWORD))->withCompanyId($company->id())->build();
        $this->clientRepository->addClient($client);

        $this->login->execute(new LoginRequest($client->email(), self::PASSWORD), $this);


        $this->assertEquals((new Notification())->addError('email', 'disabled-company'), $this->response->notification());
    }
}
