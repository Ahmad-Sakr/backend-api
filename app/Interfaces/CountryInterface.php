<?php


namespace App\Interfaces;


use App\Http\Requests\v1\CountryRequest;
use App\Models\Country;
use App\Models\Company;

interface CountryInterface
{
    public function getAllCountries(CountryRequest $request, Company $company);

    public function getCountryById(CountryRequest $request, Company $company, Country $country);

    public function storeCountry(CountryRequest $request, Company $company);

    public function deleteCountry(CountryRequest $request, Company $company, Country $country);
}
