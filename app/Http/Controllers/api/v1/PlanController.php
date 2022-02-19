<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\PlanRequest;
use App\Interfaces\PlanInterface;
use App\Models\Plan;

class PlanController extends Controller
{
    protected $planInterface;

    public function __construct(PlanInterface $planInterface)
    {
        $this->planInterface = $planInterface;
    }

    public function index(PlanRequest $request)
    {
        return $this->planInterface->getAllPlans($request);
    }

    public function store(PlanRequest $request)
    {
        return $this->planInterface->storePlan($request);
    }

    public function show(PlanRequest $request, Plan $plan)
    {
        return $this->planInterface->getPlanById($request, $plan);
    }

    public function update(PlanRequest $request, Plan $plan)
    {
        return $this->planInterface->storePlan($request, $plan);
    }

    public function destroy(PlanRequest $request, Plan $plan)
    {
        return $this->planInterface->deletePlan($request, $plan);
    }
}
