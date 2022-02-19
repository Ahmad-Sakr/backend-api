<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CountryRequest;
use App\Interfaces\CountryInterface;
use App\Models\Country;
use App\Models\Company;

class CountryController extends Controller
{
    protected $countryInterface;

    public function __construct(CountryInterface $countryInterface)
    {
        $this->countryInterface = $countryInterface;
    }

    public function index(CountryRequest $request, Company $company)
    {
        return $this->countryInterface->getAllCountries($request, $company);
    }

    public function store(CountryRequest $request, Company $company)
    {
        return $this->countryInterface->storeCountry($request, $company);
    }

    public function show(CountryRequest $request, Company $company, Country $country)
    {
        return $this->countryInterface->getCountryById($request, $company, $country);
    }

    public function destroy(CountryRequest $request, Company $company, Country $country)
    {
        return $this->countryInterface->deleteCountry($request, $company, $country);
    }
}
