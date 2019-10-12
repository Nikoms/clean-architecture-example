<?php declare(strict_types=1);

namespace Symfony4\Security;

use Seat\Domain\Client\Entity\Client;
use Seat\Domain\Client\Entity\Role;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     * @throws \Exception
     */
    public function getRoles()
    {
        switch ($this->client->role()) {
            case Role::$client:
                return ['ROLE_CLIENT'];
            case Role::$sandwich:
                return ['ROLE_SANDWICH'];
            case Role::$admin:
                return ['ROLE_ADMIN'];
        }

        throw new \Exception('No role for client '.$this->client->id());
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->client->password();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->client->email();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    public function getId()
    {
        return $this->client->id();
    }

    public function fullName()
    {
        return $this->client->firstName().' '.$this->client->lastName();
    }
}
