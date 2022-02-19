<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Role $role)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Role $role)
    {
        return true;
    }

    public function delete(User $user, Role $role)
    {
        return true;
    }
}
