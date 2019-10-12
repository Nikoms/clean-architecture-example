<?php declare(strict_types=1);

namespace Seat\Domain\Cms\Entity;

class HtmlContent
{
    private $name;
    private $html;

    public function __construct(string $name, string $html)
    {
        $this->name = $name;
        $this->html = $html;
    }

    public function name()
    {
        return $this->name;
    }

    public function html()
    {
        return $this->html;
    }
}
