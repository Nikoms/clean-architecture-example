<?php declare(strict_types=1);

namespace SeatTest\Domain\Client\UseCase\UpdateClient;

use Seat\Domain\Client\UseCase\UpdateClient\UpdateClientRequest;

class UpdateClientRequestBuilder extends UpdateClientRequest
{
    const FIRST_NAME = 'Nicolas';
    const LAST_NAME = 'De Boose';
    const PHONE_NUMBER = '0474474747';
    const EMAIL = 'nicolas@email.com';
    const PASSWORD = 'dzefzfsdf';
    const CLIENT_ID = 'my-client-id';

    public static function aRequest()
    {
        $request = new static();
        $request->firstName = self::FIRST_NAME;
        $request->lastName = self::LAST_NAME;
        $request->password = self::PASSWORD;
        $request->phoneNumber = self::PHONE_NUMBER;
        $request->email = self::EMAIL;
        $request->clientId = self::CLIENT_ID;

        return $request;
    }

    public function build()
    {
        $request = new UpdateClientRequest();
        $request->firstName = $this->firstName;
        $request->lastName = $this->lastName;
        $request->password = $this->password;
        $request->phoneNumber = $this->phoneNumber;
        $request->email = $this->email;
        $request->clientId = $this->clientId;

        return $request;
    }

    public function empty()
    {
        $this->firstName = null;
        $this->lastName = null;
        $this->password = null;
        $this->phoneNumber = null;
        $this->email = null;
        $this->clientId = null;

        return $this;
    }

    public function withClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function withPassword($password)
    {
        $this->password = $password;

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

    public function withEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function withPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
