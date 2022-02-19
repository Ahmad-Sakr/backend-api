<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\BranchRequest;
use App\Interfaces\BranchInterface;
use App\Models\Branch;
use App\Models\Company;

class BranchController extends Controller
{
    protected $branchInterface;

    public function __construct(BranchInterface $branchInterface)
    {
        $this->branchInterface = $branchInterface;
    }

    public function index(BranchRequest $request, Company $company)
    {
        return $this->branchInterface->getAllBranches($request, $company);
    }

    public function store(BranchRequest $request, Company $company)
    {
        return $this->branchInterface->storeBranch($request, $company);
    }

    public function show(BranchRequest $request, Company $company, Branch $branch)
    {
        return $this->branchInterface->getBranchById($request, $company, $branch);
    }

    public function update(BranchRequest $request, Company $company, Branch $branch)
    {
        return $this->branchInterface->storeBranch($request, $company, $branch);
    }

    public function destroy(BranchRequest $request, Company $company, Branch $branch)
    {
        return $this->branchInterface->deleteBranch($request, $company, $branch);
    }
}
