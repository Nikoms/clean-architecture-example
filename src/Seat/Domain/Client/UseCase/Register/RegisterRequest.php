<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\Register;

class RegisterRequest
{
    public $isPosted = false;
    public $firstName;
    public $lastName;
    public $store;
    public $password;
    public $phoneNumber;
    public $email;
    public $companyName;
}
