<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Branch $branch)
    {
        return $user->id === $branch->company->user_id;
    }

    public function view(User $user, Client $client, Branch $branch)
    {
        return (($user->id === $branch->company->user_id) && ($client->branch_id === $branch->id) && ($client->company_id === $branch->company_id));
    }

    public function create(User $user, Branch $branch)
    {
        return $user->id === $branch->company->user_id;
    }

    public function delete(User $user, Client $client, Branch $branch)
    {
        return (($user->id === $branch->company->user_id) && ($client->branch_id === $branch->id) && ($client->company_id === $branch->company_id));
    }
}
