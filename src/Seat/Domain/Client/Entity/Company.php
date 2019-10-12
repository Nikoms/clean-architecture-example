<?php declare(strict_types=1);

namespace Seat\Domain\Client\Entity;

class Company
{
    private $id;
    private $name;
    private $maxOrderTimeForDelivery;
    private $hasInvoice;
    private $store;
    private $isEnabled;

    public function __construct(string $id, string $name, bool $hasInvoice, string $store, bool $isEnabled, ?string $maxOrderTimeForDelivery)
    {
        $this->id = $id;
        $this->name = $name;
        $this->maxOrderTimeForDelivery = $maxOrderTimeForDelivery;
        $this->hasInvoice = $hasInvoice;
        $this->store = $store;
        $this->isEnabled = $isEnabled;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function maxOrderTimeForDelivery(): ?string
    {
        return $this->maxOrderTimeForDelivery;
    }

    public function canBeDelivered(): bool
    {
        return $this->maxOrderTimeForDelivery !== null;
    }

    public function hasInvoice(): bool
    {
        return $this->hasInvoice;
    }

    public function store(): string
    {
        return $this->store;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}
