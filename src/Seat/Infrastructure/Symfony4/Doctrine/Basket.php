<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class BusinessLunch
 * @ORM\Entity()
 */
class Basket
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $userId;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private $content;

    public function id()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function content(): array
    {
        return $this->content;
    }

    public function setContent(array $content)
    {
        $this->content = $content;
    }
}
