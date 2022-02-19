<?php


namespace App\Interfaces;


use App\Http\Requests\v1\CompanyRequest;
use App\Models\Company;

interface CompanyInterface
{
    public function getAllCompanies(CompanyRequest $request);

    public function getCompanyById(CompanyRequest $request, Company $company);

    public function storeCompany(CompanyRequest $request, Company $company = null);

    public function deleteCompany(CompanyRequest $request, Company $company);
}
