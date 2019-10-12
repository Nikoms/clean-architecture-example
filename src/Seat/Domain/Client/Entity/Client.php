<?php declare(strict_types=1);

namespace Seat\Domain\Client\Entity;

class Client
{
    private $firstName;
    private $lastName;
    private $phoneNumber;
    private $id;
    private $email;
    private $password;
    private $store;
    private $companyId;
    private $role;
    private $isEnabled;

    public function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $email,
        string $phoneNumber,
        string $password,
        string $store,
        string $role,
        bool $isEnabled,
        ?string $companyId
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->password = $password;
        $this->store = $store;
        $this->companyId = $companyId;
        $this->role = $role;
        $this->isEnabled = $isEnabled;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function store(): string
    {
        return $this->store;
    }

    public function companyId(): ?string
    {
        return $this->companyId;
    }

    public function hasCompany()
    {
        return $this->companyId !== null;
    }

    public function role(): string
    {
        return $this->role;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}
