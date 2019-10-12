<?php declare(strict_types=1);

namespace Seat\Domain\Menu\UseCase\GetMenu;

interface GetMenuPresenter
{
    public function present(GetMenuResponse $response): void;
}
