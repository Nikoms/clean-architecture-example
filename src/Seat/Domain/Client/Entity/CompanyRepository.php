<?php declare(strict_types=1);

namespace Seat\Domain\Client\Entity;

interface CompanyRepository
{
    public function getCompanyNamed(string $companyName): ?Company;

    public function addCompany(Company $company): void;

    public function getCompanyById(?string $companyId): ?Company;
}
