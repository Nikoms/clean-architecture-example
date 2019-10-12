<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Assert\NotBlank()
     */
    private $slug;

    /**
     * @var string
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Assert\NotBlank(groups={"Update"})
     */
    private $vat;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $street;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     */
    private $streetNumber;

    /**
     * @var string
     * @ORM\Column(type="string", length=4)
     * @Assert\NotBlank()
     */
    private $zipCode;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank()
     */
    private $phoneNumber;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $canBeDelivered = false;

    /**
     * @example 11:30
     * @var string
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $orderDeliveryDeadLine = '11:30:00';

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="Symfony4\Doctrine\Tournee", inversedBy="supplements")
     * @ORM\JoinColumn(name="tourneeId", referencedColumnName="id", nullable=true)
     *
     */
    private $tournee;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isEnabled = false;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string")
     */
    private $contactEmail;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $store;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $hasInvoice;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param string $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * @param string $streetNumber
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getOrderDeliveryDeadLine()
    {
        return $this->orderDeliveryDeadLine;
    }

    /**
     * @param string $orderDeliveryDeadLine
     */
    public function setOrderDeliveryDeadLine($orderDeliveryDeadLine)
    {
        $this->orderDeliveryDeadLine = $orderDeliveryDeadLine;
    }


    /**
     * @return boolean
     */
    public function canBeDelivered()
    {
        return $this->canBeDelivered;
    }

    /**
     * @param boolean $canBeDelivered
     */
    public function setCanBeDelivered($canBeDelivered)
    {
        $this->canBeDelivered = $canBeDelivered;
    }

    /**
     * @return string
     */
    public function getTournee()
    {
        return $this->tournee;
    }

    /**
     * @param string $tournee
     */
    public function setTournee($tournee)
    {
        $this->tournee = $tournee;
    }


    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param boolean $isEnabled
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param mixed $contactEmail
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    public function setAdminCommandBefore(?\DateTimeInterface $date)
    {
        if ($date) {
            $this->orderDeliveryDeadLine = $date->format('H:i:s');
        } else {
            $this->orderDeliveryDeadLine = null;
        }
    }

    public function getAdminCommandBefore()
    {
        if ($this->orderDeliveryDeadLine) {
            $time = \DateTimeImmutable::createFromFormat('H:i:s', $this->orderDeliveryDeadLine);
            if ($time !== false) {
                return $time;
            }
        }

        return null;
    }

    public function store(): string
    {
        return $this->store;
    }

    public function setStore($store)
    {
        $this->store = $store;
    }

    public function hasInvoice(): bool
    {
        return $this->hasInvoice;
    }

    public function setHasInvoice($hasInvoice)
    {
        $this->hasInvoice = $hasInvoice;
    }
}

