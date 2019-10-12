<?php
declare(strict_types=1);

namespace Symfony4\Security\Service;

use Symfony4\Security\User;
use Seat\Domain\Client\Entity\ClientRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ClientUserProvider implements UserProviderInterface
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $client = $this->clientRepository->getClientByEmail($username);
        if ($client === null || !$client->isEnabled()) {
            throw new UsernameNotFoundException();
        }

        return new User($client);
    }

    public function refreshUser(UserInterface $user)
    {
        $client = $this->clientRepository->getClientByEmail($user->getUsername());
        if ($client === null || !$client->isEnabled()) {
            throw new UsernameNotFoundException();
        }

        return $user;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
