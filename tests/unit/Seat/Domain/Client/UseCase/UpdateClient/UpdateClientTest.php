<?php declare(strict_types=1);

namespace SeatTest\Domain\Client\UseCase\UpdateClient;

use PHPUnit\Framework\TestCase;
use Seat\Domain\Client\Entity\Client;
use Seat\Domain\Client\UseCase\UpdateClient\UpdateClient;
use Seat\Domain\Client\UseCase\UpdateClient\UpdateClientPresenter;
use Seat\Domain\Client\UseCase\UpdateClient\UpdateClientResponse;
use Seat\SharedKernel\Error\Notification;
use Seat\SharedKernel\Service\Base64PasswordHasher;
use SeatTest\_Mock\Domain\Client\Entity\InMemoryClientRepository;
use SeatTest\Domain\Client\Entity\ClientBuilder;


class UpdateClientTest extends TestCase implements UpdateClientPresenter
{
    private $clientRepository;
    private $existingClient;
    private $updateClient;
    private $passwordHasher;
    /**
     * @var UpdateClientResponse
     */
    private $response;

    protected function setUp()
    {
        $this->clientRepository = new InMemoryClientRepository();
        $this->passwordHasher = new Base64PasswordHasher();
        $this->existingClient = ClientBuilder::aClient()->build();
        $this->clientRepository->addClient($this->existingClient);
        $this->updateClient = new UpdateClient($this->clientRepository, $this->passwordHasher);
    }

    public function present(UpdateClientResponse $response)
    {
        $this->response = $response;
    }

    public function test_it_updates_a_client()
    {
        $this->updateClient->execute(
            UpdateClientRequestBuilder::aRequest()->withClientId($this->existingClient->id())->build(),
            $this
        );

        $client = $this->clientRepository->getClientById($this->existingClient->id());

        $this->assertNotNull($client);
        $this->assertSame('Nicolas', $client->firstName());
        $this->assertSame('De Boose', $client->lastName());
        $this->assertSame('nicolas@email.com', $client->email());
        $this->assertSame('0474474747', $client->phoneNumber());

    }

    public function test_it_fills_the_response()
    {
        $this->updateClient->execute(
            UpdateClientRequestBuilder::aRequest()
                ->withClientId($this->existingClient->id())
                ->withFirstName('new first name')
                ->withLastName('new last name')
                ->withPhoneNumber('0474451232')
                ->withEmail('new@email.com')
                ->withPassword('new-password')
                ->build(),
            $this
        );
        $this->assertNotNull($this->response);
        $this->assertInstanceOf(Client::class, $this->response->originalClient());
        $this->assertSame('Nicolas', $this->response->originalClient()->firstName());
        $this->assertNotNull($this->response->updatedClient());
        $this->assertSame('new first name', $this->response->updatedClient()->firstName());
    }

    public function test_it_checks_fields_validity()
    {
        $this->updateClient->execute(
            UpdateClientRequestBuilder::aRequest()->empty()->build(),
            $this
        );

        $this->assertEquals(
            (new Notification())
                ->addError('clientId', 'error-notEmpty')
                ->addError('firstName', 'error-notEmpty')
                ->addError('lastName', 'error-notEmpty')
                ->addError('email', 'error-notEmpty')
                ->addError('phoneNumber', 'error-notEmpty'),
            $this->response->notification()
        );
    }

    public function test_it_checks_email_validity()
    {
        $this->updateClient->execute(
            UpdateClientRequestBuilder::aRequest()->withClientId($this->existingClient->id())->withEmail('invalid')->build(),
            $this
        );

        $this->assertEquals(
            (new Notification())
                ->addError('email', 'invalid-email'),
            $this->response->notification()
        );
    }

    public function test_it_may_not_update_the_password()
    {
        $this->updateClient->execute(
            UpdateClientRequestBuilder::aRequest()
                ->withClientId($this->existingClient->id())
                ->withPassword(null)
                ->build(),
            $this
        );

        $client = $this->clientRepository->getClientById($this->existingClient->id());

        $this->assertNotNull($client);
        $this->assertSame($this->existingClient->password(), $client->password());
    }

    public function test_it_hashes_the_password()
    {
        $this->updateClient->execute(
            UpdateClientRequestBuilder::aRequest()
                ->withClientId($this->existingClient->id())
                ->withPassword('new-password')
                ->build(),
            $this
        );

        $client = $this->clientRepository->getClientById($this->existingClient->id());

        $this->assertNotNull($client);
        $this->assertSame($this->passwordHasher->hash('new-password'), $client->password());
        $this->assertTrue($this->response->hasPasswordChanged());
    }

    public function test_it_fails_when_the_client_does_not_exist()
    {
        $this->updateClient->execute(
            UpdateClientRequestBuilder::aRequest()->withClientId('unknown-client-id')->build(),
            $this
        );

        $this->assertEquals(
            (new Notification())->addError('clientId', 'unknown-client'),
            $this->response->notification()
        );
    }
}
