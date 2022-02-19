<?php


namespace App\Repositories;


use App\Helpers\ResponseMessages;
use App\Http\Requests\v1\CompanyRequest;
use App\Http\Resources\v1\CompanyResource;
use App\Interfaces\CompanyInterface;
use App\Models\Company;
use App\Traits\ApiExceptionHandler;
use App\Traits\ApiResponder;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanyRepository implements CompanyInterface
{
    use ApiResponder, ApiExceptionHandler;

    public function getAllCompanies(CompanyRequest $request)
    {
        return $this->success(CompanyResource::collection(request()->user()->companies),"List of Companies", Response::HTTP_OK);
    }

    public function getCompanyById(CompanyRequest $request, Company $company)
    {
        return $this->success(new CompanyResource($company));
    }

    public function storeCompany(CompanyRequest $request, Company $company = null)
    {
        DB::beginTransaction();
        try {
            $create = ($company === null);
            $data = $request->validated();

            if($create) {
                $data['slug'] = Str::slug($data['name']);
                $data['user_id'] = $request->user()->id;
                $company = Company::query()->create($data);
            }
            else {
                $company->update($data);
            }

            DB::commit();
            return $this->success(new CompanyResource($company),
                $create ? ResponseMessages::CREATED : ResponseMessages::UPDATED,
                $create ? Response::HTTP_CREATED : Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function deleteCompany(CompanyRequest $request, Company $company)
    {
        DB::beginTransaction();
        try {
            $company->delete();

            DB::commit();
            return $this->success([],ResponseMessages::DELETED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
