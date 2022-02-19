<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BranchPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Company $company)
    {
        return $user->id === $company->user_id;
    }

    public function view(User $user, Branch $branch, Company $company)
    {
        return (($user->id === $company->user_id) && ($branch->company_id === $company->id));
    }

    public function create(User $user, Company $company)
    {
        return $user->id === $company->user_id;
    }

    public function update(User $user, Branch $branch, Company $company)
    {
        return (($user->id === $company->user_id) && ($branch->company_id === $company->id));
    }

    public function delete(User $user, Branch $branch, Company $company)
    {
        return (($user->id === $company->user_id) && ($branch->company_id === $company->id));
    }
}
