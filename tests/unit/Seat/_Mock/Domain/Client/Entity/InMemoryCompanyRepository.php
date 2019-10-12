<?php declare(strict_types=1);

namespace SeatTest\_Mock\Domain\Client\Entity;

use Seat\Domain\Client\Entity\Company;
use Seat\Domain\Client\Entity\CompanyRepository;

class InMemoryCompanyRepository implements CompanyRepository
{

    /** @var Company[] */
    private $companies = [];

    public function getCompanyNamed(string $companyName): ?Company
    {
        $find = function (Company $company) use ($companyName) {
            return $company->name() === $companyName;
        };
        $foundCompanies = array_values(array_filter($this->companies, $find));

        if (count($foundCompanies) === 1) {
            return $foundCompanies[0];
        }

        return null;
    }

    public function addCompany(Company $company): void
    {
        $this->companies[] = $company;
    }

    public function getCompanyById(?string $companyId): ?Company
    {
        $find = function (Company $company) use ($companyId) {
            return $company->id() === $companyId;
        };
        $foundCompanies = array_values(array_filter($this->companies, $find));

        if (count($foundCompanies) === 1) {
            return $foundCompanies[0];
        }

        return null;
    }
}
