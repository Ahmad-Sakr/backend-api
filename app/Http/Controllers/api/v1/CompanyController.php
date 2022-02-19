<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CompanyRequest;
use App\Interfaces\CompanyInterface;
use App\Models\Company;

class CompanyController extends Controller
{
    protected $companyInterface;

    public function __construct(CompanyInterface $companyInterface)
    {
        $this->companyInterface = $companyInterface;
    }

    public function index(CompanyRequest $request)
    {
        return $this->companyInterface->getAllCompanies($request);
    }

    public function store(CompanyRequest $request)
    {
        return $this->companyInterface->storeCompany($request);
    }

    public function show(CompanyRequest $request, Company $company)
    {
        return $this->companyInterface->getCompanyById($request, $company);
    }

    public function update(CompanyRequest $request, Company $company)
    {
        return $this->companyInterface->storeCompany($request, $company);
    }

    public function destroy(CompanyRequest $request, Company $company)
    {
        return $this->companyInterface->deleteCompany($request, $company);
    }
}
