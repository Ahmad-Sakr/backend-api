<?php


namespace App\Repositories;


use App\Helpers\ResponseMessages;
use App\Http\Requests\v1\PlanRequest;
use App\Http\Resources\v1\PlanResource;
use App\Interfaces\PlanInterface;
use App\Models\Plan;
use App\Traits\ApiExceptionHandler;
use App\Traits\ApiResponder;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PlanRepository implements PlanInterface
{
    use ApiResponder, ApiExceptionHandler;

    public function getAllPlans(PlanRequest $request)
    {
        return $this->success(PlanResource::collection(Plan::all()),"List of Plans", Response::HTTP_OK);
    }

    public function getPlanById(PlanRequest $request, Plan $plan)
    {
        return $this->success(new PlanResource($plan));
    }

    public function storePlan(PlanRequest $request, Plan $plan = null)
    {
        DB::beginTransaction();
        try {
            $create = ($plan === null);
            $data = $request->validated();

            if($create) {
                $plan = Plan::query()->create($data);
            }
            else {
                $plan->update($data);
            }

            DB::commit();
            return $this->success(new PlanResource($plan),
                $create ? ResponseMessages::CREATED : ResponseMessages::UPDATED,
                $create ? Response::HTTP_CREATED : Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function deletePlan(PlanRequest $request, Plan $plan)
    {
        DB::beginTransaction();
        try {
            $plan->delete();

            DB::commit();
            return $this->success([],ResponseMessages::DELETED, Response::HTTP_OK);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
