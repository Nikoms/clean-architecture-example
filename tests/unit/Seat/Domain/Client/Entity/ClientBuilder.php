<?php declare(strict_types=1);

namespace SeatTest\Domain\Client\Entity;

use Ramsey\Uuid\Uuid;
use Seat\Domain\Client\Entity\Client;


class ClientBuilder
{
    private $firstName = 'Nicolas';
    private $lastName = 'De Boose';
    private $phoneNumber = '0474 45 78 12';
    private $id = null;
    private $email = 'nico@email.com';
    private $password = 'dqfn4qsdf';
    private $store = 'my-store';
    private $companyId = null;
    private $role = 'client';
    private $isEnabled = true;

    public function withFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function withLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function withPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function withId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function withEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function withPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function withStore(string $store)
    {
        $this->store = $store;

        return $this;
    }

    public function withCompanyId(string $companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function withRole(string $role)
    {
        $this->role = $role;

        return $this;
    }

    public function withDisabled()
    {
        $this->isEnabled = false;

        return $this;
    }

    public function build()
    {
        $id = $this->id ?? Uuid::uuid4()->toString();

        return new Client(
            $id,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->phoneNumber,
            $this->password,
            $this->store,
            $this->role,
            $this->isEnabled,
            $this->companyId
        );
    }

    public static function aClient()
    {
        return new ClientBuilder();
    }
}
