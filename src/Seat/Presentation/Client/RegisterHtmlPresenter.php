<?php declare(strict_types=1);

namespace Seat\Presentation\Client;

use Seat\Domain\Client\UseCase\Register\RegisterPresenter;
use Seat\Domain\Client\UseCase\Register\RegisterResponse;

class RegisterHtmlPresenter implements RegisterPresenter
{
    private $viewModel;

    public function __construct()
    {
    }

    public function present(RegisterResponse $response): void
    {
        $this->viewModel = new RegisterHtmlViewModel();
        $this->viewModel->clientSaved = $response->client() !== null;

        foreach ($response->notification()->getErrors() as $error) {
            $this->viewModel->errors[$error->fieldName()] = $error->message();
        }
    }

    public function viewModel()
    {
        return $this->viewModel;
    }
}
