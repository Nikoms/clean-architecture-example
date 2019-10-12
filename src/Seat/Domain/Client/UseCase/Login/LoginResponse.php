<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\Login;

use Seat\Domain\Client\Entity\Client;
use Seat\SharedKernel\Error\Notification;

class LoginResponse
{
    private $notification;

    /** @var ?Client */
    private $client;

    public function __construct()
    {
        $this->notification = new Notification();
    }

    public function addError(string $fieldName, string $error)
    {
        $this->notification->addError($fieldName, $error);
    }

    public function notification(): Notification
    {
        return $this->notification;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function client(): ?Client
    {
        return $this->client;
    }
}
