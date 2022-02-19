<?php

namespace Tests\Unit;

use App\Http\Resources\v1\CountryResource;
use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use Database\Seeders\PlansSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CountryTest extends TestCase
{
    Use RefreshDatabase, WithFaker;

    protected $connectionsToTransact = ['testing'];

    public function test_user_can_list_countries_of_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Countries For Each Company
        $users = User::factory(2)
                    ->has(Company::factory(1)
                        ->has(Country::factory(10)))
                    ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get Countries Of Company
        $company = $loggedUser->companies()->first();
        $countries = CountryResource::collection($company->countries)->toArray(null);

        //Get Response
        $response = $this->get(route('companies.countries.index', $company));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_countries = $response->json()['data'];
        $this->assertEquals($countries, $response_countries);

        //Test JSON Structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [ 'id', 'ref', 'name' ],
            ]
        ]);
    }

    public function test_user_cannot_list_countries_of_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Countries For Each Company
        $users = User::factory(2)
                    ->has(Company::factory(1)
                        ->has(Country::factory(10)))
                    ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        //Get Countries Of Company
        $anotherCompany = $anotherUser->companies()->first();

        //Get Response
        $response = $this->get(route('companies.countries.index', $anotherCompany));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_list_single_country_of_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Countries For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Country::factory(1)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get Countries Of Company
        $company = $loggedUser->companies()->first();
        $country = (new CountryResource(Country::query()->where('id', 1)->first()))->toArray(null);

        //Get Response
        $response = $this->get(route('companies.countries.show', ['company' => $company, 'country' => 1]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_country = $response->json()['data'];
        $this->assertEquals($country, $response_country);
    }

    public function test_user_cannot_list_single_country_of_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Countries For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Country::factory(1)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        //Get Countries Of Company
        $company = $loggedUser->companies()->first();
        $anotherCompany = $anotherUser->companies()->first();

        //Get Response
        $response = $this->get(route('companies.countries.show', ['company' => $company, 'country' => 2]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Get Response
        $response = $this->get(route('companies.countries.show', ['company' => $anotherCompany, 'country' => 1]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_create_new_countries_in_his_company()
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
        $response = $this->json('POST', route('companies.countries.store', $company), [
            'data' => [
                [
                    'ref'   => '01',
                    'name'  => 'Country 01'
                ],
                [
                    'ref'   => '02',
                    'name'  => 'Country 02'
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check Countries Count
        $this->assertDatabaseCount('countries', 2);
    }

    public function test_user_cannot_create_new_countries_in_another_user_company()
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
        $response = $this->json('POST', route('companies.countries.store', $anotherCompany), [
            'data' => [
                [
                    'ref'   => '01',
                    'name'  => 'Country 01'
                ],
                [
                    'ref'   => '02',
                    'name'  => 'Country 02'
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check Countries Count
        $this->assertDatabaseCount('countries', 0);
    }

    public function test_user_can_update_existing_countries_in_his_company()
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

        //Post Save First Country
        $response = $this->json('POST', route('companies.countries.store', $company), [
            'data' => [
                [
                    'ref'   => '01',
                    'name'  => 'Country 01'
                ]
            ],
        ]);

        //Post Update First Country
        $response = $this->json('POST', route('companies.countries.store', $company), [
            'data' => [
                [
                    'ref'   => '01',
                    'name'  => 'New Country 01'
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check New Country Created
        $this->assertDatabaseCount('countries', 1);

        //Check New Country Name
        $this->assertEquals('New Country 01', Country::query()->first()->name);
    }

    public function test_user_can_delete_country_in_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Countries For Each Company
        $users = User::factory(2)
                    ->has(Company::factory(1)
                        ->has(Country::factory(10)))
                    ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);
        $company = $loggedUser->companies()->first();

        //Get First Country
        $country = $company->countries->first();

        //Post
        $response = $this->json('DELETE', route('companies.countries.destroy', ['company' => $company, 'country' => $country]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check Countries Count
        $this->assertDatabaseCount('countries', 19);

        //Check Countries Count Of Logged In User
        $this->assertEquals(9, $company->countries()->count());
    }

    public function test_user_cannot_delete_country_in_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Countries For Each Company
        $users = User::factory(2)
                    ->has(Company::factory(1)
                        ->has(Country::factory(10)))
                    ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        $anotherCompany = $anotherUser->companies()->first();

        //Get First Country
        $country = $anotherCompany->countries->first();

        //Post
        $response = $this->json('DELETE', route('companies.countries.destroy', ['company' => $anotherCompany, 'country' => $country]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check Check Countries Count
        $this->assertDatabaseCount('countries', 20);
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Company::query()->truncate();
        Country::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedPlans()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->seed(PlansSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
