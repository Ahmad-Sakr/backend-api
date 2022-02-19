<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CurrencyRequest;
use App\Interfaces\CurrencyInterface;
use App\Models\Company;
use App\Models\Currency;

class CurrencyController extends Controller
{
    protected $currencyInterface;

    public function __construct(CurrencyInterface $currencyInterface)
    {
        $this->currencyInterface = $currencyInterface;
    }

    public function index(CurrencyRequest $request, Company $company)
    {
        return $this->currencyInterface->getAllCurrencies($request, $company);
    }

    public function store(CurrencyRequest $request, Company $company)
    {
        return $this->currencyInterface->storeCurrency($request, $company);
    }

    public function show(CurrencyRequest $request, Company $company, Currency $currency)
    {
        return $this->currencyInterface->getCurrencyById($request, $company, $currency);
    }

    public function destroy(CurrencyRequest $request, Company $company, Currency $currency)
    {
        return $this->currencyInterface->deleteCurrency($request, $company, $currency);
    }
}
