<?php declare(strict_types=1);

namespace Seat\Domain\Menu\Entity;

class Category
{
    private $id;
    private $name;
    private $description;

    public function __construct(string $id, string $name, ?string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }
}
