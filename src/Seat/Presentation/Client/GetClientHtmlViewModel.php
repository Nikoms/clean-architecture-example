<?php declare(strict_types=1);

namespace Seat\Presentation\Client;

class GetClientHtmlViewModel
{
    private $editableClient;

    public function editableClient(): EditableClient
    {
        return $this->editableClient;
    }

    public function makeEditableClient(string $firstName, string $lastName, string $email, string $phoneNumber)
    {
        $this->editableClient = new EditableClient();
        $this->editableClient->firstName = $firstName;
        $this->editableClient->lastName = $lastName;
        $this->editableClient->email = $email;
        $this->editableClient->phoneNumber = $phoneNumber;
    }
}
