<?php


namespace App\Repositories;


use App\Helpers\ResponseMessages;
use App\Http\Requests\v1\StateRequest;
use App\Http\Resources\v1\StateResource;
use App\Interfaces\StateInterface;
use App\Models\Company;
use App\Models\State;
use App\Traits\ApiExceptionHandler;
use App\Traits\ApiResponder;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StateRepository implements StateInterface
{
    use ApiResponder, ApiExceptionHandler;

    public function getAllStates(StateRequest $request, Company $company)
    {
        return $this->success(StateResource::collection($company->states),
            "List of States of Company " . $company->display_name,
            Response::HTTP_OK);
    }

    public function getStateById(StateRequest $request, Company $company, State $state)
    {
        return $this->success(new StateResource($state));
    }

    public function storeState(StateRequest $request, Company $company)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            foreach ($data["data"] as $record) {
                $state = $company->states()->where('ref', $record['ref'])->first();
                if($state) {
                    //Update Existing Record
                    $state->update($record);
                }
                else {
                    //Save New Record
                    $company->states()->create($record);
                }
            }

            DB::commit();
            return $this->success([], ResponseMessages::CREATED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function deleteState(StateRequest $request, Company $company, State $state)
    {
        DB::beginTransaction();
        try {
            $state->delete();

            DB::commit();
            return $this->success([],ResponseMessages::DELETED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
