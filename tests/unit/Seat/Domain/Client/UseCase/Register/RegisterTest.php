<?php declare(strict_types=1);

namespace SeatTest\Domain\Client\UseCase\Register;

use PHPUnit\Framework\TestCase;
use Seat\Domain\Client\UseCase\Register\Register;
use Seat\Domain\Client\UseCase\Register\RegisterPresenter;
use Seat\Domain\Client\UseCase\Register\RegisterResponse;
use Seat\SharedKernel\Error\Notification;
use Seat\SharedKernel\Service\Base64PasswordHasher;
use SeatTest\_Mock\Domain\Client\Entity\InMemoryClientRepository;
use SeatTest\_Mock\Domain\Client\Entity\InMemoryCompanyRepository;
use SeatTest\_Mock\Seat\SharedKernel\Service\IdGeneratorMock;
use SeatTest\Domain\Client\Entity\ClientBuilder;
use SeatTest\Domain\Client\Entity\CompanyBuilder;

class RegisterTest extends TestCase implements RegisterPresenter
{
    private $idGenerator;
    private $clientRepository;
    private $register;
    private $passwordHasher;
    private $companyRepository;
    /** @var RegisterResponse */
    private $response;

    public function present(RegisterResponse $response): void
    {
        $this->response = $response;
    }

    protected function setUp()
    {
        $this->clientRepository = new InMemoryClientRepository();
        $this->companyRepository = new InMemoryCompanyRepository();
        $this->idGenerator = new IdGeneratorMock();
        $this->passwordHasher = new Base64PasswordHasher();
        $this->register = new Register(
            $this->idGenerator,
            $this->clientRepository,
            $this->companyRepository,
            $this->passwordHasher
        );
    }

    public function test_saves_the_client_in_the_database_with_a_hashed_password()
    {
        $request = RegisterRequestBuilder::aRequest()->build();
        $this->register->execute($request, $this);

        $this->assertEquals(
            ClientBuilder::aClient()
                ->withId($this->idGenerator->lastId)
                ->withFirstName($request->firstName)
                ->withLastName($request->lastName)
                ->withEmail($request->email)
                ->withPhoneNumber($request->phoneNumber)
                ->withPassword(base64_encode($request->password))
                ->withStore($request->store)
                ->build(),
            $this->clientRepository->getClientByEmail($request->email)
        );
    }

    public function test_fails_when_email_already_exist()
    {
        $request = RegisterRequestBuilder::aRequest()->build();
        $this->clientRepository->addClient(ClientBuilder::aClient()->withEmail($request->email)->build());
        $this->register->execute($request, $this);

        $shouldBe = (new Notification())->addError('email', 'email-already-used');
        $this->assertEquals($shouldBe, $this->response->notification());
    }

    public function test_a_user_can_not_be_linked_to_an_missing_company()
    {
        $request = RegisterRequestBuilder::aRequest()->withCompanyName('Missing company')->build();
        $this->register->execute($request, $this);


        $shouldBe = (new Notification())->addError('companyName', 'unknown-company');
        $this->assertEquals($shouldBe, $this->response->notification());
    }

    public function test_a_user_can_be_linked_to_an_existing_company()
    {
        $company = CompanyBuilder::aCompany()->build();
        $this->companyRepository->addCompany($company);

        $request = RegisterRequestBuilder::aRequest()->withCompanyName($company->name())->build();

        $this->register->execute($request, $this);

        $this->assertNotNull($this->response->client());
        $this->assertSame($company->id(), $this->response->client()->companyId());
        $this->assertEquals(
            ClientBuilder::aClient()
                ->withId($this->idGenerator->lastId)
                ->withFirstName($request->firstName)
                ->withLastName($request->lastName)
                ->withEmail($request->email)
                ->withPhoneNumber($request->phoneNumber)
                ->withPassword(base64_encode($request->password))
                ->withStore($request->store)
                ->withCompanyId($company->id())
                ->build(),
            $this->clientRepository->getClientByEmail($request->email)
        );
    }

    public function test_when_a_company_is_linked_the_user_inherits_from_its_store()
    {
        $company = CompanyBuilder::aCompany()->store('waterloo')->build();
        $this->companyRepository->addCompany($company);
        $request = RegisterRequestBuilder::aRequest()->withCompanyName($company->name())->withStore('la-hulpe')->build();

        $this->register->execute($request, $this);

        $this->assertNotNull($this->response->client());
        $this->assertSame($company->store(), $this->response->client()->store());
    }

    public function test_a_user_can_not_register_in_a_disabled_company()
    {
        $company = CompanyBuilder::aCompany()->disabled()->build();
        $this->companyRepository->addCompany($company);
        $request = RegisterRequestBuilder::aRequest()->withCompanyName($company->name())->build();

        $this->register->execute($request, $this);

        $shouldBe = (new Notification())->addError('companyName', 'disabled-company');
        $this->assertEquals($shouldBe, $this->response->notification());
    }

    public function test_the_request_is_validated()
    {
        $request = RegisterRequestBuilder::aRequest()->empty()->withIsPosted(true)->build();
        $this->register->execute($request, $this);

        $shouldBe = (new Notification())
            ->addError('firstName', 'error-notEmpty')
            ->addError('lastName', 'error-notEmpty')
            ->addError('email', 'error-notEmpty')
            ->addError('phoneNumber', 'error-notEmpty')
            ->addError('password', 'error-notEmpty')
            ->addError('store', 'error-notEmpty');

        $this->assertEquals($shouldBe, $this->response->notification());
    }

    public function test_firstname_lastname_and_password_must_be_a_string()
    {
        $request = RegisterRequestBuilder::aRequest()
            ->withFirstName(1)->withLastName(1)->withEmail(1)->withPassword(1)->withStore(1)->build();

        $this->register->execute($request, $this);

        $shouldBe = (new Notification())
            ->addError('firstName', 'error-string')
            ->addError('lastName', 'error-string')
            ->addError('email', 'invalid-email')
            ->addError('password', 'error-string')
            ->addError('store', 'error-choice');

        $this->assertEquals($shouldBe, $this->response->notification());

    }

    public function test_email_must_be_valid()
    {
        $request = RegisterRequestBuilder::aRequest()->withEmail('invalid-email')->build();
        $this->register->execute($request, $this);

        $shouldBe = (new Notification())->addError('email', 'invalid-email');
        $this->assertEquals($shouldBe, $this->response->notification());
    }

    public function test_invalid_store_throws_an_error()
    {
        $request = RegisterRequestBuilder::aRequest()->withStore('invalid')->build();

        $this->register->execute($request, $this);


        $shouldBe = (new Notification())->addError('store', 'error-choice');
        $this->assertEquals($shouldBe, $this->response->notification());
    }

    /**
     * @dataProvider stores
     */
    public function test_store_must_be_la_hulpe_or_waterloo($store)
    {
        $request = RegisterRequestBuilder::aRequest()->withStore($store)->build();
        $this->register->execute($request, $this);
        $this->assertFalse($this->response->notification()->hasError());
    }

    public function stores(): array
    {
        return [['la-hulpe'], ['waterloo']];
    }
}
