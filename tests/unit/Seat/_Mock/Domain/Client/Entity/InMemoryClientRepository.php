<?php declare(strict_types=1);

namespace SeatTest\_Mock\Domain\Client\Entity;

use Seat\Domain\Client\Entity\Client;
use Seat\Domain\Client\Entity\ClientRepository;

class InMemoryClientRepository implements ClientRepository
{
    /**
     * @var Client[]
     */
    private $clients = [];

    public function addClient(Client $client)
    {
        $this->clients[] = $client;
    }

    public function getRegisterClient(string $email, string $password): ?Client
    {
        $find = function (Client $client) use ($email, $password) {
            return $client->email() === $email && $client->password() === $password;
        };

        $clientsFound = array_values(array_filter($this->clients, $find));
        if (count($clientsFound) === 1) {
            return $clientsFound[0];
        }

        return null;
    }

    public function getClientByEmail(string $email): ?Client
    {
        $find = function (Client $client) use ($email) {
            return $client->email() === $email;
        };

        $clientsFound = array_values(array_filter($this->clients, $find));
        if (count($clientsFound) === 1) {
            return $clientsFound[0];
        }

        return null;
    }

    public function getClientById(string $id): ?Client
    {
        $find = function (Client $client) use ($id) {
            return $client->id() === $id;
        };

        $clientsFound = array_values(array_filter($this->clients, $find));
        if (count($clientsFound) === 1) {
            return $clientsFound[0];
        }

        return null;
    }

    public function updateClient(Client $client): void
    {
        for ($i = 0; $i < count($this->clients); $i++) {
            if ($this->clients[$i]->id() === $client->id()) {
                $this->clients[$i] = $client;
                break;
            }
        }

    }
}
