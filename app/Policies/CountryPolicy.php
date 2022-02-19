<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CountryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Company $company)
    {
        return $user->id === $company->user_id;
    }

    public function view(User $user, Country $country, Company $company)
    {
        return (($user->id === $company->user_id) && ($country->company_id === $company->id));
    }

    public function create(User $user, Company $company)
    {
        return $user->id === $company->user_id;
    }

    public function delete(User $user, Country $country, Company $company)
    {
        return (($user->id === $company->user_id) && ($country->company_id === $company->id));
    }
}
