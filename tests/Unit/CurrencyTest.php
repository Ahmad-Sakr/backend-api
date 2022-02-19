<?php

namespace Tests\Unit;

use App\Http\Resources\v1\CurrencyResource;
use App\Models\Company;
use App\Models\Currency;
use App\Models\User;
use Database\Seeders\PlansSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    Use RefreshDatabase, WithFaker;

    protected $connectionsToTransact = ['testing'];

    public function test_user_can_list_currencies_of_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Currencies For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Currency::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get Currencies Of Company
        $company = $loggedUser->companies()->first();
        $currencies = CurrencyResource::collection($company->currencies)->toArray(null);

        //Get Response
        $response = $this->get(route('companies.currencies.index', $company));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_currencies = $response->json()['data'];
        $this->assertEquals($currencies, $response_currencies);

        //Test JSON Structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [ 'id', 'ref', 'rate' ],
            ]
        ]);
    }

    public function test_user_cannot_list_currencies_of_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Currencies For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Currency::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        //Get Currencies Of Company
        $anotherCompany = $anotherUser->companies()->first();

        //Get Response
        $response = $this->get(route('companies.currencies.index', $anotherCompany));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_list_single_currency_of_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Currencies For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Currency::factory(1)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get Countries Of Company
        $company = $loggedUser->companies()->first();
        $currency = (new CurrencyResource(Currency::query()->where('id', 1)->first()))->toArray(null);

        //Get Response
        $response = $this->get(route('companies.currencies.show', ['company' => $company, 'currency' => 1]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_currency = $response->json()['data'];
        $this->assertEquals($currency, $response_currency);
    }

    public function test_user_cannot_list_single_currency_of_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Currencies For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Currency::factory(1)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        //Get Countries Of Company
        $company = $loggedUser->companies()->first();
        $anotherCompany = $anotherUser->companies()->first();

        //Get Response
        $response = $this->get(route('companies.currencies.show', ['company' => $company, 'currency' => 2]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Get Response
        $response = $this->get(route('companies.currencies.show', ['company' => $anotherCompany, 'currency' => 1]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_create_new_currencies_in_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User
        $users = User::factory(2)
            ->has(Company::factory(1))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);
        $company = $loggedUser->companies()->first();

        //Post
        $response = $this->json('POST', route('companies.currencies.store', $company), [
            'data' => [
                [
                    'ref'   => 'USD',
                    'rate'  => 1500
                ],
                [
                    'ref'   => 'EUR',
                    'rate'  => 2000
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check Currencies Count
        $this->assertDatabaseCount('currencies', 2);
    }

    public function test_user_cannot_create_new_currencies_in_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User
        $users = User::factory(2)
            ->has(Company::factory(1))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        $anotherCompany = $anotherUser->companies()->first();

        //Post
        $response = $this->json('POST', route('companies.currencies.store', $anotherCompany), [
            'data' => [
                [
                    'ref'   => 'USD',
                    'rate'  => 1500
                ],
                [
                    'ref'   => 'EUR',
                    'rate'  => 2000
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check Currencies Count
        $this->assertDatabaseCount('currencies', 0);
    }

    public function test_user_can_update_existing_currencies_in_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User
        $users = User::factory(2)
            ->has(Company::factory(1))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);
        $company = $loggedUser->companies()->first();

        //Post Save First Currency
        $response = $this->json('POST', route('companies.currencies.store', $company), [
            'data' => [
                [
                    'ref'   => 'USD',
                    'rate'  => 1500
                ]
            ],
        ]);

        //Post Update First Currency
        $response = $this->json('POST', route('companies.currencies.store', $company), [
            'data' => [
                [
                    'ref'   => 'USD',
                    'rate'  => 1508
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check New Currency Created
        $this->assertDatabaseCount('currencies', 1);

        //Check New Currency Name
        $this->assertEquals(1508, Currency::query()->first()->rate);
    }

    public function test_user_can_delete_currency_in_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Currencies For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Currency::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);
        $company = $loggedUser->companies()->first();

        //Get First Currency
        $currency = $company->currencies->first();

        //Post
        $response = $this->json('DELETE', route('companies.currencies.destroy', ['company' => $company, 'currency' => $currency]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check Currencies Count
        $this->assertDatabaseCount('currencies', 19);

        //Check Currencies Count Of Logged In User
        $this->assertEquals(9, $company->currencies()->count());
    }

    public function test_user_cannot_delete_currency_in_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Currencies For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Currency::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        $anotherCompany = $anotherUser->companies()->first();

        //Get First Currency
        $currency = $anotherCompany->currencies->first();

        //Post
        $response = $this->json('DELETE', route('companies.currencies.destroy', ['company' => $anotherCompany, 'currency' => $currency]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check Check Currencies Count
        $this->assertDatabaseCount('currencies', 20);
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Currency::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedPlans()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->seed(PlansSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
