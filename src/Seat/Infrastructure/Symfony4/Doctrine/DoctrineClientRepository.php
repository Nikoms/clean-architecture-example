<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Symfony4\Doctrine\Company;
use Symfony4\Doctrine\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Seat\Domain\Client\Entity\Client;
use Seat\Domain\Client\Entity\ClientRepository;

class DoctrineClientRepository extends ServiceEntityRepository implements ClientRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function addClient(Client $client)
    {
        $user = new User();
        $user->setId($client->id());
        $user->setEmail($client->email());
        $user->setFirstName($client->firstName());
        $user->setLastName($client->lastName());
        $user->setPhoneNumber($client->phoneNumber());
        $user->setPassword($client->password());
        $user->setStore($client->store());
        $user->setRole($client->role());
        $user->setIsEnabled($client->isEnabled());

        if ($client->hasCompany()) {
            $companyForeign = $this->getEntityManager()->getReference(Company::class, $client->companyId());
            $user->setCompany($companyForeign);
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);

    }

    public function getClientByEmail(string $email): ?Client
    {
        /** @var User $user */
        $user = $this->findOneBy(['email' => $email]);
        if ($user === null) {
            return null;
        }

        $companyId = $user->getCompany() === null
            ? null
            : $this->getEntityManager()->getUnitOfWork()->getEntityIdentifier($user->getCompany())['id'].'';

        return new Client(
            $user->id(),
            $user->firstName(),
            $user->lastName(),
            $user->email(),
            $user->phoneNumber(),
            $user->password(),
            $user->store(),
            $user->role(),
            $user->isEnabled(),
            $companyId
        );
    }

    public function getClientById(string $id): ?Client
    {
        /** @var User $user */
        $user = $this->findOneBy(['id' => $id]);
        if ($user === null) {
            return null;
        }

        $companyId = $user->getCompany() === null
            ? null
            : $this->getEntityManager()->getUnitOfWork()->getEntityIdentifier($user->getCompany())['id'].'';

        return new Client(
            $user->id(),
            $user->firstName(),
            $user->lastName(),
            $user->email(),
            $user->phoneNumber(),
            $user->password(),
            $user->store(),
            $user->role(),
            $user->isEnabled(),
            $companyId
        );
    }

    public function updateClient(Client $client): void
    {
        $user = $this->findOneBy(['id' => $client->id()]);
        $user->setEmail($client->email());
        $user->setFirstName($client->firstName());
        $user->setLastName($client->lastName());
        $user->setPhoneNumber($client->phoneNumber());
        $user->setPassword($client->password());

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);
    }
}
