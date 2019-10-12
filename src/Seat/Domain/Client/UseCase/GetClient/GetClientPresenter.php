<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\GetClient;

interface GetClientPresenter
{
    public function present(GetClientResponse $response): void;
}
