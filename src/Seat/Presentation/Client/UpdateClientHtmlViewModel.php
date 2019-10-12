<?php declare(strict_types=1);

namespace Seat\Presentation\Client;

class UpdateClientHtmlViewModel
{
    public $errors = [];
    public $redirectToThankYouPage = false;
    public $redirectToLogout = false;
    public $notificationMessage = null;
    public $notificationType = null;
    public $hasPasswordChanged = false;

    public function displayNotification(string $type, string $message)
    {
        $this->notificationType = $type;
        $this->notificationMessage = $message;
    }
}
