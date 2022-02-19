<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Plan $plan)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Plan $plan)
    {
        return true;
    }

    public function delete(User $user, Plan $plan)
    {
        return true;
    }
}
