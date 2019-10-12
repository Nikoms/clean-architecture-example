<?php declare(strict_types=1);

namespace Seat\SharedKernel\Error;

class Error
{
    private $fieldName;
    private $message;

    public function __construct(string $fieldName, string $message)
    {
        $this->fieldName = $fieldName;
        $this->message = $message;
    }

    public function __toString()
    {
        return $this->fieldName.':'.$this->message;
    }

    public function fieldName(): string
    {
        return $this->fieldName;
    }

    public function message(): string
    {
        return $this->message;
    }
}
