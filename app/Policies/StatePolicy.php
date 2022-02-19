<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\State;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Company $company)
    {
        return $user->id === $company->user_id;
    }

    public function view(User $user, State $state, Company $company)
    {
        return (($user->id === $company->user_id) && ($state->company_id === $company->id));
    }

    public function create(User $user, Company $company)
    {
        return $user->id === $company->user_id;
    }

    public function delete(User $user, State $state, Company $company)
    {
        return (($user->id === $company->user_id) && ($state->company_id === $company->id));
    }
}
