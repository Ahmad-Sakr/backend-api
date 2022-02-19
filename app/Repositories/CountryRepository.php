<?php


namespace App\Repositories;


use App\Helpers\ResponseMessages;
use App\Http\Requests\v1\CountryRequest;
use App\Http\Resources\v1\CountryResource;
use App\Interfaces\CountryInterface;
use App\Models\Company;
use App\Models\Country;
use App\Traits\ApiExceptionHandler;
use App\Traits\ApiResponder;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CountryRepository implements CountryInterface
{
    use ApiResponder, ApiExceptionHandler;

    public function getAllCountries(CountryRequest $request, Company $company)
    {
        return $this->success(CountryResource::collection($company->countries),
                            "List of Countries of Company " . $company->display_name,
                            Response::HTTP_OK);
    }

    public function getCountryById(CountryRequest $request, Company $company, Country $country)
    {
        return $this->success(new CountryResource($country));
    }

    public function storeCountry(CountryRequest $request, Company $company)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            foreach ($data["data"] as $record) {
                $country = $company->countries()->where('ref', $record['ref'])->first();
                if($country) {
                    //Update Existing Record
                    $country->update($record);
                }
                else {
                    //Save New Record
                    $company->countries()->create($record);
                }
            }

            DB::commit();
            return $this->success([], ResponseMessages::CREATED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function deleteCountry(CountryRequest $request, Company $company, Country $country)
    {
        DB::beginTransaction();
        try {
            $country->delete();

            DB::commit();
            return $this->success([],ResponseMessages::DELETED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
