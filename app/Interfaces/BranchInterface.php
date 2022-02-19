<?php


namespace App\Interfaces;


use App\Http\Requests\v1\BranchRequest;
use App\Models\Branch;
use App\Models\Company;

interface BranchInterface
{
    public function getAllBranches(BranchRequest $request, Company $company);

    public function getBranchById(BranchRequest $request, Company $company, Branch $branch);

    public function storeBranch(BranchRequest $request, Company $company, Branch $branch = null);

    public function deleteBranch(BranchRequest $request, Company $company, Branch $branch);
}
