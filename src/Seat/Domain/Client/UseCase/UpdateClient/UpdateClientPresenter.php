<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\UpdateClient;

interface UpdateClientPresenter
{
    public function present(UpdateClientResponse $response);
}
