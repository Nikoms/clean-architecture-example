<?php declare(strict_types=1);

namespace Seat\Domain\Client\Entity;

interface ClientRepository
{
    public function addClient(Client $client);

    public function getClientByEmail(string $email): ?Client;

    public function getClientById(string $id): ?Client;

    public function updateClient(Client $client): void;
}
