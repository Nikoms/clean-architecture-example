<?php declare(strict_types=1);

namespace Symfony4\Doctrine;

use Symfony4\Doctrine\Company as CompanyEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Seat\Domain\Client\Entity\Company;
use Seat\Domain\Client\Entity\CompanyRepository;

class DoctrineCompanyRepository extends ServiceEntityRepository implements CompanyRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyEntity::class);
    }

    public function getCompanyNamed(string $companyName): ?Company
    {
        /** @var CompanyEntity $company */
        $company = $this->findOneBy(['name' => $companyName]);
        if ($company === null) {
            return null;
        }

        return new Company(
            $company->getId().'',
            $company->getName(),
            $company->hasInvoice(),
            $company->store(),
            $company->isEnabled(),
            $company->getOrderDeliveryDeadLine()
        );
    }

    public function addCompany(Company $company): void
    {
        $companyEntity = new CompanyEntity();
        $companyEntity->setId($company->id());
        $companyEntity->setName($company->name());

        $this->getEntityManager()->persist($companyEntity);
        $this->getEntityManager()->flush($companyEntity);
    }

    public function getCompanyById(?string $companyId): ?Company
    {
        /** @var CompanyEntity $company */
        $company = $this->findOneBy(['id' => $companyId]);
        if ($company === null) {
            return null;
        }

        return new Company(
            $company->getId().'',
            $company->getName(),
            $company->hasInvoice(),
            $company->store(),
            $company->isEnabled(),
            $company->getOrderDeliveryDeadLine()
        );
    }
}
