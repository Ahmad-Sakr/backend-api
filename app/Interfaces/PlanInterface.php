<?php


namespace App\Interfaces;


use App\Http\Requests\v1\PlanRequest;
use App\Models\Plan;

interface PlanInterface
{
    public function getAllPlans(PlanRequest $request);

    public function getPlanById(PlanRequest $request, Plan $plan);

    public function storePlan(PlanRequest $request, Plan $plan = null);

    public function deletePlan(PlanRequest $request, Plan $plan);
}
