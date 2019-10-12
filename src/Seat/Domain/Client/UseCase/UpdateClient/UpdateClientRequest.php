<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\UpdateClient;

use Seat\Presentation\Client\EditableClient;

class UpdateClientRequest extends EditableClient
{
    public static function fromEditable(EditableClient $nullableRequest)
    {
        $request = new UpdateClientRequest();
        $request->firstName = $nullableRequest->firstName;
        $request->lastName = $nullableRequest->lastName;
        $request->password = $nullableRequest->password;
        $request->phoneNumber = $nullableRequest->phoneNumber;
        $request->email = $nullableRequest->email;
        $request->clientId = $nullableRequest->clientId;

        return $request;
    }

    public function byClientId(string $clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }
}
