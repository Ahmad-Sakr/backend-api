<?php

namespace Tests\Unit;

use App\Http\Resources\v1\RoleResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RoleTest extends TestCase
{
    Use RefreshDatabase, WithFaker;

    protected $connectionsToTransact = ['testing'];

    public function test_can_list_roles()
    {
        //Clear Table
        $this->truncate();

        //Create Admin
        $this->createAdmin();

        //Factory
        $roles = RoleResource::collection(Role::factory(5)->create())->toArray(null);

        //Get Response
        $response = $this->get(route('roles.index'));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_roles = $response->json()['data'];
        $this->assertEquals($roles, $response_roles);

        //Test JSON Structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [ 'id', 'name' ],
            ]
        ]);
    }

    public function test_can_create_role()
    {
        //Clear Table
        $this->truncate();

        //Create Admin
        $this->createAdmin();

        //Post
        $response = $this->json('POST', route('roles.store'), [
            'name' => $this->faker->word,
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_CREATED);

        //Check New Role Created
        $this->assertDatabaseCount('roles', 1);
    }

    public function test_name_is_required_for_role()
    {
        //Clear Table
        $this->truncate();

        //Create Admin
        $this->createAdmin();

        //Post
        $response = $this->json('POST', route('roles.store'), [
            'name' => '',
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        //Test Response Status
        $response->assertJsonValidationErrors('name', 'data.errors');

        //Check Role Not Created
        $this->assertDatabaseCount('roles', 0);
    }

    public function test_can_update_role()
    {
        //Clear Table
        $this->truncate();

        //Create Admin
        $this->createAdmin();

        //Factory
        $role = Role::factory()->create();

        //Update Role
        $response = $this->json('PATCH', route('roles.update', ['role' => $role]), [
            'name' => 'New Role',
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check New User Created
        $new_role = Role::query()->first();
        $this->assertEquals('New Role', $new_role->name);
    }

    public function test_can_delete_role()
    {
        //Clear Table
        $this->truncate();

        //Create Admin
        $this->createAdmin();

        //Factory
        $role = Role::factory()->create();

        //Delete Role
        $response = $this->json('DELETE', route('roles.destroy', ['role' => $role]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check Database Table Count
        $this->assertDatabaseCount('roles', 0);
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createAdmin()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);
    }
}

