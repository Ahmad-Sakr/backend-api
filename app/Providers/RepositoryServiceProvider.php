<?php

namespace App\Providers;

use App\Interfaces\AuthInterface;
use App\Interfaces\BranchInterface;
use App\Interfaces\CompanyInterface;
use App\Interfaces\CountryInterface;
use App\Interfaces\CurrencyInterface;
use App\Interfaces\ClientInterface;
use App\Interfaces\PlanInterface;
use App\Interfaces\RoleInterface;
use App\Interfaces\StateInterface;
use App\Repositories\AuthRepository;
use App\Repositories\BranchRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\ClientRepository;
use App\Repositories\PlanRepository;
use App\Repositories\RoleRepository;
use App\Repositories\StateRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(RoleInterface::class,RoleRepository::class);
        $this->app->bind(PlanInterface::class,PlanRepository::class);
        $this->app->bind(AuthInterface::class,AuthRepository::class);
        $this->app->bind(CompanyInterface::class,CompanyRepository::class);
        $this->app->bind(BranchInterface::class,BranchRepository::class);

        $this->app->bind(CountryInterface::class,CountryRepository::class);
        $this->app->bind(StateInterface::class,StateRepository::class);
        $this->app->bind(CurrencyInterface::class,CurrencyRepository::class);
        $this->app->bind(ClientInterface::class,ClientRepository::class);
    }
}
