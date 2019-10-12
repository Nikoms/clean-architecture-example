<?php declare(strict_types=1);

namespace Seat\Domain\Client\UseCase\Login;

interface LoginPresenter
{
    public function present(LoginResponse $response);
}
