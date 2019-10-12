<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\Register;

interface RegisterPresenter
{
    public function present(RegisterResponse $response): void;
}
