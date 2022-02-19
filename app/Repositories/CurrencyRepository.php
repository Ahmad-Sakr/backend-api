<?php


namespace App\Repositories;


use App\Helpers\ResponseMessages;
use App\Http\Requests\v1\CurrencyRequest;
use App\Http\Resources\v1\CurrencyResource;
use App\Interfaces\CurrencyInterface;
use App\Models\Company;
use App\Models\Currency;
use App\Traits\ApiExceptionHandler;
use App\Traits\ApiResponder;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CurrencyRepository implements CurrencyInterface
{
    use ApiResponder, ApiExceptionHandler;

    public function getAllCurrencies(CurrencyRequest $request, Company $company)
    {
        return $this->success(CurrencyResource::collection($company->currencies),
            "List of Currencies of Company " . $company->display_name,
            Response::HTTP_OK);
    }

    public function getCurrencyById(CurrencyRequest $request, Company $company, Currency $currency)
    {
        return $this->success(new CurrencyResource($currency));
    }

    public function storeCurrency(CurrencyRequest $request, Company $company)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            foreach ($data["data"] as $record) {
                $currency = $company->currencies()->where('ref', $record['ref'])->first();
                if($currency) {
                    //Update Existing Record
                    $currency->update($record);
                }
                else {
                    //Save New Record
                    $company->currencies()->create($record);
                }
            }

            DB::commit();
            return $this->success([], ResponseMessages::CREATED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function deleteCurrency(CurrencyRequest $request, Company $company, Currency $currency)
    {
        DB::beginTransaction();
        try {
            $currency->delete();

            DB::commit();
            return $this->success([],ResponseMessages::DELETED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
