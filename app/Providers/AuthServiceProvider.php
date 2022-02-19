<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Plan;
use App\Models\Role;
use App\Models\State;
use App\Policies\BranchPolicy;
use App\Policies\ClientPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\CountryPolicy;
use App\Policies\CurrencyPolicy;
use App\Policies\PlanPolicy;
use App\Policies\RolePolicy;
use App\Policies\StatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Role::class => RolePolicy::class,
         Plan::class => PlanPolicy::class,
         Company::class => CompanyPolicy::class,
         Branch::class => BranchPolicy::class,
         Country::class => CountryPolicy::class,
         State::class => StatePolicy::class,
         Currency::class => CurrencyPolicy::class,
         Client::class => ClientPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
