<?php


namespace App\Interfaces;


use App\Http\Requests\v1\StateRequest;
use App\Models\Company;
use App\Models\State;

interface StateInterface
{
    public function getAllStates(StateRequest $request, Company $company);

    public function getStateById(StateRequest $request, Company $company, State $state);

    public function storeState(StateRequest $request, Company $company);

    public function deleteState(StateRequest $request, Company $company, State $state);
}
