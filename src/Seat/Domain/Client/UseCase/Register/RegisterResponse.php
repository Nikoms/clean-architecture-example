<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\Register;

use Seat\Domain\Client\Entity\Client;
use Seat\SharedKernel\Error\Notification;

class RegisterResponse
{
    private $client;
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

    public function setRegisteredClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    public function client(): ?Client
    {
        return $this->client;
    }
}
