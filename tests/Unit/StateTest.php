<?php

namespace Tests\Unit;

use App\Http\Resources\v1\StateResource;
use App\Models\Company;
use App\Models\State;
use App\Models\User;
use Database\Seeders\PlansSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StateTest extends TestCase
{
    Use RefreshDatabase, WithFaker;

    protected $connectionsToTransact = ['testing'];

    public function test_user_can_list_states_of_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + States For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(State::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get States Of Company
        $company = $loggedUser->companies()->first();
        $states = StateResource::collection($company->states)->toArray(null);

        //Get Response
        $response = $this->get(route('companies.states.index', $company));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_states = $response->json()['data'];
        $this->assertEquals($states, $response_states);

        //Test JSON Structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [ 'id', 'ref', 'name' ],
            ]
        ]);
    }

    public function test_user_cannot_list_states_of_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + States For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(State::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        //Get States Of Company
        $anotherCompany = $anotherUser->companies()->first();

        //Get Response
        $response = $this->get(route('companies.states.index', $anotherCompany));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_list_single_state_of_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + States For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(State::factory(1)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get Countries Of Company
        $company = $loggedUser->companies()->first();
        $state = (new StateResource(State::query()->where('id', 1)->first()))->toArray(null);

        //Get Response
        $response = $this->get(route('companies.states.show', ['company' => $company, 'state' => 1]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_state = $response->json()['data'];
        $this->assertEquals($state, $response_state);
    }

    public function test_user_cannot_list_single_state_of_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + States For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(State::factory(1)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        //Get Countries Of Company
        $company = $loggedUser->companies()->first();
        $anotherCompany = $anotherUser->companies()->first();

        //Get Response
        $response = $this->get(route('companies.states.show', ['company' => $company, 'state' => 2]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Get Response
        $response = $this->get(route('companies.states.show', ['company' => $anotherCompany, 'state' => 1]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_create_new_states_in_his_company()
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
        $response = $this->json('POST', route('companies.states.store', $company), [
            'data' => [
                [
                    'ref'   => '01',
                    'name'  => 'State 01'
                ],
                [
                    'ref'   => '02',
                    'name'  => 'State 02'
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check States Count
        $this->assertDatabaseCount('states', 2);
    }

    public function test_user_cannot_create_new_states_in_another_user_company()
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
        $response = $this->json('POST', route('companies.states.store', $anotherCompany), [
            'data' => [
                [
                    'ref'   => '01',
                    'name'  => 'State 01'
                ],
                [
                    'ref'   => '02',
                    'name'  => 'State 02'
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check States Count
        $this->assertDatabaseCount('states', 0);
    }

    public function test_user_can_update_existing_states_in_his_company()
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

        //Post Save First State
        $response = $this->json('POST', route('companies.states.store', $company), [
            'data' => [
                [
                    'ref'   => '01',
                    'name'  => 'State 01'
                ]
            ],
        ]);

        //Post Update First State
        $response = $this->json('POST', route('companies.states.store', $company), [
            'data' => [
                [
                    'ref'   => '01',
                    'name'  => 'New State 01'
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check New State Created
        $this->assertDatabaseCount('states', 1);

        //Check New State Name
        $this->assertEquals('New State 01', State::query()->first()->name);
    }

    public function test_user_can_delete_state_in_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + States For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(State::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);
        $company = $loggedUser->companies()->first();

        //Get First State
        $state = $company->states->first();

        //Post
        $response = $this->json('DELETE', route('companies.states.destroy', ['company' => $company, 'state' => $state]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check States Count
        $this->assertDatabaseCount('states', 19);

        //Check States Count Of Logged In User
        $this->assertEquals(9, $company->states()->count());
    }

    public function test_user_cannot_delete_state_in_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + States For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(State::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        $anotherCompany = $anotherUser->companies()->first();

        //Get First State
        $state = $anotherCompany->states->first();

        //Post
        $response = $this->json('DELETE', route('companies.states.destroy', ['company' => $anotherCompany, 'state' => $state]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check Check States Count
        $this->assertDatabaseCount('states', 20);
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Company::query()->truncate();
        State::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedPlans()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->seed(PlansSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
