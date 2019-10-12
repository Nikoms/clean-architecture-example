<?php declare(strict_types=1);

namespace Seat\Presentation\Client;

use Seat\Domain\Client\UseCase\GetClient\GetClientPresenter;
use Seat\Domain\Client\UseCase\GetClient\GetClientResponse;

class GetClientHtmlPresenter implements GetClientPresenter
{
    private $viewModel;

    public function present(GetClientResponse $response): void
    {
        $this->viewModel = new GetClientHtmlViewModel();
        $this->viewModel->makeEditableClient(
            $response->client()->firstName(),
            $response->client()->lastName(),
            $response->client()->email(),
            $response->client()->phoneNumber()
        );
    }

    public function viewModel()
    {
        return $this->viewModel;
    }
}
