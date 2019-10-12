<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\GetClient;

class GetClientRequest
{
    public $clientId;

    public function __construct(string $clientId)
    {
        $this->clientId = $clientId;
    }
}
