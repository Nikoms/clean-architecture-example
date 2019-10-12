<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\UpdateClient;

use Seat\Domain\Client\Entity\Client;
use Seat\SharedKernel\Error\Notification;

class UpdateClientResponse
{
    /** @var Client */
    private $updatedClient;
    /** @var Client */
    private $originalClient;
    private $request;
    private $hasPasswordChanged = false;
    private $note;

    public function __construct()
    {
        $this->note = new Notification();
    }

    public function addError(string $fieldName, string $error)
    {
        $this->note->addError($fieldName, $error);
    }

    public function notification(): Notification
    {
        return $this->note;
    }

    public function setUpdatedClient(Client $client)
    {
        $this->updatedClient = $client;
        $this->hasPasswordChanged = $client->password() !== $this->originalClient->password();

        return $this;
    }

    public function hasPasswordChanged(): bool
    {
        return $this->hasPasswordChanged;
    }

    public function updatedClient(): ?Client
    {
        return $this->updatedClient;
    }

    public function setOriginalClient(?Client $originalClient)
    {
        $this->originalClient = $originalClient;
    }

    public function originalClient(): ?Client
    {
        return $this->originalClient;
    }

    public function request(): ?UpdateClientRequest
    {
        return $this->request;
    }

    public function setRequest(UpdateClientRequest $request)
    {
        $this->request = $request;

        return $this;
    }
}
