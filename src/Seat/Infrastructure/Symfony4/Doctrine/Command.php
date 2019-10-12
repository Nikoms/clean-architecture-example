<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use Seat\Domain\Basket\Model\OrderType;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class Command
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
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

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @var array
     * @ORM\Column(type="json_array")
     */
    private $orderType;

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

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function date()
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date)
    {
        $this->date = $date->setTimezone(new \DateTimeZone('Europe/Brussels'));
    }

    public function setOrderType(OrderType $orderType)
    {
        $this->orderType = ['name' => $orderType->name(), 'time' => $orderType->time()];
    }

    public function getOrderTypeString()
    {
        $str = $this->orderType['name'];
        if (!empty($this->orderType['time'])) {
            $str .= ' ('.substr($this->orderType['time'], 0, -3).')';
        }

        return $str;
    }

    public function getName()
    {
        return $this->content['name'];
    }

    public function getOption()
    {
        if (!empty($this->content['option'])) {
            return $this->content['option']['name'];
        }

        return '-';
    }

    public function getSupplements()
    {
        $supplementNames = [];
        $supplements = $this->content['supplements'] ?? [];
        foreach ($supplements as $supplement) {
            $supplementNames[] = $supplement['name'];
        }

        $str = join(', ', $supplementNames);

        return !empty($str) ? $str : '-';
    }

    public function getComment()
    {
        return !empty($this->content['comment']) ? $this->content['comment'] : '-';
    }

    public function getQuantity()
    {
        return $this->content['quantity'];
    }
}
