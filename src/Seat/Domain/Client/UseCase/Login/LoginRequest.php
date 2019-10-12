<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\Login;

class LoginRequest
{
    public $email;
    public $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
