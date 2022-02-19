<?php


namespace App\Interfaces;


use App\Http\Requests\v1\CurrencyRequest;
use App\Models\Company;
use App\Models\Currency;

interface CurrencyInterface
{
    public function getAllCurrencies(CurrencyRequest $request, Company $company);

    public function getCurrencyById(CurrencyRequest $request, Company $company, Currency $currency);

    public function storeCurrency(CurrencyRequest $request, Company $company);

    public function deleteCurrency(CurrencyRequest $request, Company $company, Currency $currency);
}
