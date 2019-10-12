<?php declare(strict_types=1);

namespace Seat\Domain\Cms\Entity;

interface HtmlContentRepository
{
    public function getNamed(string $name): HtmlContent;
}
