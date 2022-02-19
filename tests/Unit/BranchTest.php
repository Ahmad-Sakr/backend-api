<?php

namespace Tests\Unit;

use App\Http\Resources\v1\BranchResource;
use App\Models\Company;
use App\Models\Branch;
use App\Models\User;
use Database\Seeders\PlansSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BranchTest extends TestCase
{
    Use RefreshDatabase, WithFaker;

    protected $connectionsToTransact = ['testing'];

    public function test_user_can_list_branches_of_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Users + Company For Each User + Branches For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get Branches Of Company
        $company = $loggedUser->companies()->first();
        $branches = BranchResource::collection($company->branches)->toArray(null);

        //Get Response
        $response = $this->get(route('companies.branches.index', $company));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_branches = $response->json()['data'];
        $this->assertEquals($branches, $response_branches);

        //Test JSON Structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [ 'id', 'name', 'display_name' ],
            ]
        ]);
    }

    public function test_user_cannot_list_branches_of_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Branches For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        //Get Branches Of Company
        $anotherCompany = $anotherUser->companies()->first();

        //Get Response
        $response = $this->get(route('companies.branches.index', $anotherCompany));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_list_single_branch_of_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Branches For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(1)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get Countries Of Company
        $company = $loggedUser->companies()->first();
        $branch = (new BranchResource(Branch::query()->where('id', 1)->first()))->toArray(null);

        //Get Response
        $response = $this->get(route('companies.branches.show', ['company' => $company, 'branch' => 1]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_branch = $response->json()['data'];
        $this->assertEquals($branch, $response_branch);
    }

    public function test_user_cannot_list_single_branch_of_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Branches For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(1)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        //Get Countries Of Company
        $company = $loggedUser->companies()->first();
        $anotherCompany = $anotherUser->companies()->first();

        //Get Response
        $response = $this->get(route('companies.branches.show', ['company' => $company, 'branch' => 2]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Get Response
        $response = $this->get(route('companies.branches.show', ['company' => $anotherCompany, 'branch' => 1]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_create_new_branches_in_his_company()
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
        $response = $this->json('POST', route('companies.branches.store', $company), [
            'name'          => $this->faker->word(),
            'display_name'  => $this->faker->word()
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_CREATED);

        //Check Branches Count
        $this->assertDatabaseCount('branches', 1);
    }

    public function test_user_cannot_create_new_branches_in_another_user_company()
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
        $response = $this->json('POST', route('companies.branches.store', $anotherCompany), [
            'name'          => $this->faker->word(),
            'display_name'  => $this->faker->word()
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check Branches Count
        $this->assertDatabaseCount('branches', 0);
    }

    public function test_name_is_required_for_branch()
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
        $response = $this->json('POST', route('companies.branches.store', $company), [
            'name'          => '',
            'display_name'  => $this->faker->word()
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        //Test Response Status
        $response->assertJsonValidationErrors('name', 'data.errors');

        //Check Branches Count
        $this->assertDatabaseCount('branches', 0);
    }

    public function test_display_name_is_required_for_branch()
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
        $response = $this->json('POST', route('companies.branches.store', $company), [
            'name'          => $this->faker->word(),
            'display_name'  => ''
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        //Test Response Status
        $response->assertJsonValidationErrors('display_name', 'data.errors');

        //Check Branches Count
        $this->assertDatabaseCount('branches', 0);
    }

    public function test_name_is_unique_for_branch_in_same_company()
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

        //Post First Branch
        $branch_name = $this->faker->word();
        $this->json('POST', route('companies.branches.store', $company), [
            'name'          => $branch_name,
            'display_name'  => $this->faker->word()
        ]);

        //Post Second Branch With Same Name
        $response = $this->json('POST', route('companies.branches.store', $company), [
            'name'          => $branch_name,
            'display_name'  => $this->faker->word()
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        //Test Response Status
        $response->assertJsonValidationErrors('name', 'data.errors');

        //Check Branches Count
        $this->assertDatabaseCount('branches', 1);
    }

    public function test_name_is_not_unique_for_branch_in_different_company()
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

        //Post First Branch
        $branch_name = $this->faker->word();
        $this->json('POST', route('companies.branches.store', $company), [
            'name'          => $branch_name,
            'display_name'  => $this->faker->word()
        ]);

        //Log With Another User
        $loggedUser = $users[1];
        $this->actingAs($loggedUser);
        $company = $loggedUser->companies()->first();

        //Post Second Branch With Same Name
        $response = $this->json('POST', route('companies.branches.store', $company), [
            'name'          => $branch_name,
            'display_name'  => $this->faker->word()
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_CREATED);

        //Check Branches Count
        $this->assertDatabaseCount('branches', 2);
    }

    public function test_user_can_update_existing_branch_in_his_company()
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

        //Post Save First Branch
        $this->json('POST', route('companies.branches.store', $company), [
            'name'          => $this->faker->word(),
            'display_name'  => $this->faker->word()
        ]);
        $branch = Branch::query()->first();

        //Post Update First Branch
        $response = $this->json('PATCH', route('companies.branches.update', ['company' => $company, 'branch' => $branch]), [
            'display_name'  => 'Update Display Name'
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check New Branch Created
        $this->assertDatabaseCount('branches', 1);

        //Check New Branch Name
        $this->assertEquals('Update Display Name', Branch::query()->first()->display_name);
    }

    public function test_user_cannot_update_existing_branch_in_another_user_company()
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

        $company = $loggedUser->companies()->first();
        $anotherCompany = $anotherUser->companies()->first();

        //Post Save First Branch
        $this->json('POST', route('companies.branches.store', $company), [
            'name'          => $this->faker->word(),
            'display_name'  => $this->faker->word()
        ]);
        $branch = Branch::query()->first();

        //Post Update First Branch
        $response = $this->json('PATCH', route('companies.branches.update', ['company' => $anotherCompany, 'branch' => $branch]), [
            'display_name'  => 'Update Display Name'
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check New Branch Name
        $this->assertNotEquals('Update Display Name', Branch::query()->first()->display_name);
    }

    public function test_user_can_delete_branch_in_his_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Branches For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);
        $company = $loggedUser->companies()->first();

        //Get First Branch
        $branch = $company->branches->first();

        //Post
        $response = $this->json('DELETE', route('companies.branches.destroy', ['company' => $company, 'branch' => $branch]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check Branches Count
        $this->assertDatabaseCount('branches', 19);

        //Check Branches Count Of Logged In User
        $this->assertEquals(9, $company->branches()->count());
    }

    public function test_user_cannot_delete_branch_in_another_user_company()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedPlans();

        //Create Clients + Company For Each User + Branches For Each Company
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(10)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        $anotherCompany = $anotherUser->companies()->first();

        //Get First Branch
        $branch = $anotherCompany->branches->first();

        //Post
        $response = $this->json('DELETE', route('companies.branches.destroy', ['company' => $anotherCompany, 'branch' => $branch]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check Check Branches Count
        $this->assertDatabaseCount('branches', 20);
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Branch::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedPlans()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->seed(PlansSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
