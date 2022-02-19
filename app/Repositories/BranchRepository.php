<?php


namespace App\Repositories;


use App\Helpers\ResponseMessages;
use App\Http\Requests\v1\BranchRequest;
use App\Http\Resources\v1\BranchResource;
use App\Interfaces\BranchInterface;
use App\Models\Branch;
use App\Models\Company;
use App\Traits\ApiExceptionHandler;
use App\Traits\ApiResponder;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BranchRepository implements BranchInterface
{
    use ApiResponder, ApiExceptionHandler;

    public function getAllBranches(BranchRequest $request, Company $company)
    {
        return $this->success(BranchResource::collection($company->branches),"List of Branches of Company " . $company->display_name, Response::HTTP_OK);
    }

    public function getBranchById(BranchRequest $request, Company $company, Branch $branch)
    {
        return $this->success(new BranchResource($branch));
    }

    public function storeBranch(BranchRequest $request, Company $company, Branch $branch = null)
    {
        DB::beginTransaction();
        try {
            $create = ($branch === null);
            $data = $request->validated();

            if($create) {
                $data['slug'] = Str::slug($data['name']);
                $branch = $company->branches()->create($data);
            }
            else {
                $branch->update($data);
            }

            DB::commit();
            return $this->success(new BranchResource($branch),
                $create ? ResponseMessages::CREATED : ResponseMessages::UPDATED,
                $create ? Response::HTTP_CREATED : Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function deleteBranch(BranchRequest $request, Company $company, Branch $branch)
    {
        DB::beginTransaction();
        try {
            $branch->delete();

            DB::commit();
            return $this->success([],ResponseMessages::DELETED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
