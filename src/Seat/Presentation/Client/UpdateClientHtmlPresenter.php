<?php declare(strict_types=1);

namespace Seat\Presentation\Client;

use Seat\Domain\Client\UseCase\UpdateClient\UpdateClientPresenter;
use Seat\Domain\Client\UseCase\UpdateClient\UpdateClientResponse;

class UpdateClientHtmlPresenter implements UpdateClientPresenter
{
    private $viewModel;

    public function __construct()
    {
        $this->viewModel = new UpdateClientHtmlViewModel();
    }

    public function present(UpdateClientResponse $response)
    {
        $this->viewModel = new UpdateClientHtmlViewModel();

        if ($response->updatedClient() !== null) {
            $this->viewModel->redirectToThankYouPage = true;
            $this->viewModel->displayNotification('success', 'my-info-updated');
            $this->viewModel->redirectToLogout = $response->hasPasswordChanged();

            if ($this->viewModel->redirectToLogout) {
                $this->viewModel->displayNotification('warning', 'new-password-login-needed');
            }
        }

        foreach ($response->notification()->getErrors() as $error) {
            $this->viewModel->errors[$error->fieldName()] = $error->message();
        }
    }

    public function viewModel()
    {
        return $this->viewModel;
    }
}
