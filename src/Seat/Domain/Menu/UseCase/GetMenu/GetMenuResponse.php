<?php declare(strict_types=1);

namespace Seat\Domain\Menu\UseCase\GetMenu;

use Seat\Domain\Menu\Model\MenuLine;

class GetMenuResponse
{
    public $menuLines;

    /** @param MenuLine[] $menuLines */
    public function __construct(array $menuLines)
    {
        $this->menuLines = $menuLines;
    }
}
