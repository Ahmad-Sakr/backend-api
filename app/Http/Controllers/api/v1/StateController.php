<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StateRequest;
use App\Interfaces\StateInterface;
use App\Models\Company;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    protected $stateInterface;

    public function __construct(StateInterface $stateInterface)
    {
        $this->stateInterface = $stateInterface;
    }

    public function index(StateRequest $request, Company $company)
    {
        return $this->stateInterface->getAllStates($request, $company);
    }

    public function store(StateRequest $request, Company $company)
    {
        return $this->stateInterface->storeState($request, $company);
    }

    public function show(StateRequest $request, Company $company, State $state)
    {
        return $this->stateInterface->getStateById($request, $company, $state);
    }

    public function destroy(StateRequest $request, Company $company, State $state)
    {
        return $this->stateInterface->deleteState($request, $company, $state);
    }
}
