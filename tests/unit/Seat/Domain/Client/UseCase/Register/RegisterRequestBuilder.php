<?php declare(strict_types=1);

namespace SeatTest\Domain\Client\UseCase\Register;

use Seat\Domain\Client\UseCase\Register\RegisterRequest;

class RegisterRequestBuilder extends RegisterRequest
{
    const FIRST_NAME = 'Nico';
    const LAST_NAME = 'last name';
    const PHONE_NUMBER = '0474474747';
    const EMAIL = 'nico@email.com';
    const PASSWORD = 'dzefzfsdf';
    const STORE = 'la-hulpe';

    public static function aRequest()
    {
        $request = new static();
        $request->isPosted = true;
        $request->firstName = self::FIRST_NAME;
        $request->lastName = self::LAST_NAME;
        $request->store = self::STORE;
        $request->password = self::PASSWORD;
        $request->phoneNumber = self::PHONE_NUMBER;
        $request->email = self::EMAIL;

        return $request;
    }

    public function build()
    {
        $request = new RegisterRequest();
        $request->isPosted = $this->isPosted;
        $request->firstName = $this->firstName;
        $request->lastName = $this->lastName;
        $request->store = $this->store;
        $request->password = $this->password;
        $request->phoneNumber = $this->phoneNumber;
        $request->email = $this->email;
        $request->companyName = $this->companyName;

        return $request;
    }

    public function empty()
    {
        $this->isPosted = false;
        $this->firstName = null;
        $this->lastName = null;
        $this->store = null;
        $this->password = null;
        $this->phoneNumber = null;
        $this->email = null;

        return $this;
    }

    public function withIsPosted($isPosted)
    {
        $this->isPosted = $isPosted;

        return $this;
    }

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

    public function withStore($store)
    {
        $this->store = $store;

        return $this;
    }

    public function withPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function withPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function withEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function withCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }
}
