<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurrencyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Company $company)
    {
        return $user->id === $company->user_id;
    }

    public function view(User $user, Currency $currency, Company $company)
    {
        return (($user->id === $company->user_id) && ($currency->company_id === $company->id));
    }

    public function create(User $user, Company $company)
    {
        return $user->id === $company->user_id;
    }

    public function delete(User $user, Currency $currency, Company $company)
    {
        return (($user->id === $company->user_id) && ($currency->company_id === $company->id));
    }
}
