<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\BranchController;
use App\Http\Controllers\api\v1\ClientController;
use App\Http\Controllers\api\v1\CompanyController;
use App\Http\Controllers\api\v1\CountryController;
use App\Http\Controllers\api\v1\CurrencyController;
use App\Http\Controllers\api\v1\PlanController;
use App\Http\Controllers\api\v1\RoleController;
use App\Http\Controllers\api\v1\StateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| v1
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'v1'], function(){
    /*
    |--------------------------------------------------------------------------
    | 01. Auth
    |--------------------------------------------------------------------------
    */
    Route::post('/auth/register', [AuthController::class, 'register'])->name('api.v1.auth.register');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('api.v1.auth.login');
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::get('/auth/logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');
        Route::get('/auth/user', [AuthController::class, 'getLoggedInUser'])->name('api.v1.auth.user');
    });

    /*
    |--------------------------------------------------------------------------
    | 02. Roles
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::resource('roles', RoleController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | 03. Plans
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::resource('plans', PlanController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | 04. Companies
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::resource('companies', CompanyController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | 05. Branches
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::resource('companies.branches', BranchController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | 06. Countries
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::resource('companies.countries', CountryController::class)->only(['index', 'show', 'store']);
        Route::delete('/companies/{company}/countries/{country:ref}', [CountryController::class, 'destroy'])->name('companies.countries.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | 07. States
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::resource('companies.states', StateController::class)->only(['index', 'show', 'store']);
        Route::delete('/companies/{company}/states/{state:ref}', [StateController::class, 'destroy'])->name('companies.states.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | 08. Currencies
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::resource('companies.currencies', CurrencyController::class)->only(['index', 'show', 'store']);
        Route::delete('/companies/{company}/currencies/{currency:ref}', [CurrencyController::class, 'destroy'])->name('companies.currencies.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | 09. Clients
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::resource('branches.clients', ClientController::class)->only(['index', 'show', 'store']);
        Route::delete('/branches/{branch}/clients/{client:ref}', [ClientController::class, 'destroy'])->name('branches.clients.destroy');
    });
});
