<?php declare(strict_types=1);

namespace SeatTest\Domain\Client\Entity;

use Ramsey\Uuid\Uuid;
use Seat\Domain\Client\Entity\Company;
use Seat\Domain\Client\Entity\Store;

class CompanyBuilder
{
    private $id = null;
    private $name = 'Namico';
    private $hasInvoice = false;
    private $store = null;
    private $maxOrderTimeForDelivery = null;
    private $isEnabled = true;


    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    public function invoiced()
    {
        $this->hasInvoice = true;

        return $this;
    }

    public function store($store)
    {
        $this->store = $store;

        return $this;
    }

    public function deliverable($maxOrderTimeForDelivery)
    {
        $this->maxOrderTimeForDelivery = $maxOrderTimeForDelivery;

        return $this;
    }

    public function build()
    {
        $id = $this->id ?? Uuid::uuid4()->toString();
        $store = $this->store ?? Store::$laHulpe;

        return new Company($id, $this->name, $this->hasInvoice, $store, $this->isEnabled, $this->maxOrderTimeForDelivery);
    }

    public static function aCompany()
    {
        return new CompanyBuilder();
    }

    public function disabled()
    {
        $this->isEnabled = false;

        return $this;
    }
}
