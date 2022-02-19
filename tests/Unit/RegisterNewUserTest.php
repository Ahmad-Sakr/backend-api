<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegisterNewUserTest extends TestCase
{
    Use RefreshDatabase, WithFaker;

    protected $connectionsToTransact = ['testing'];

    public function test_can_register_new_user()
    {
        //Truncate Database
        $this->truncate();

        //Seed Roles
        $this->seedClass(RolesSeeder::class);

        //Post Data
        $response = $this->json('POST', route('api.v1.auth.register'), $this->typicalUserData());

        //Test Response Status
        $response->assertStatus(Response::HTTP_CREATED);

        //Check Database Count
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('role_user', 1);

        //Check Registered User Data
        $user = User::query()->first();
        $this->assertEquals('user', $user->username);

        //Check User Roles
        $role = Role::query()->where('name', 'User')->first();
        $this->assertEquals(1, $user->roles()->count());
        $this->assertEquals($role->id, $user->roles()->first()->id);
    }

    public function test_if_username_is_unique_while_registering_new_user()
    {
        //Truncate Database
        $this->truncate();

        //Seed Roles
        $this->seedClass(RolesSeeder::class);

        //Register First User
        $this->json('POST', route('api.v1.auth.register'), $this->typicalUserData());

        //Register Second User
        $response = $this->json('POST', route('api.v1.auth.register'), $this->typicalUserData());

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        //Test Validation Message
        $response->assertJsonValidationErrors('username', 'data.errors');

        //Check Database Count
        $this->assertDatabaseCount('users', 1);
    }

    public function test_if_email_is_unique_while_registering_new_user()
    {
        //Truncate Database
        $this->truncate();

        //Seed Roles
        $this->seedClass(RolesSeeder::class);

        //Register First User
        $this->json('POST', route('api.v1.auth.register'), $this->typicalUserData());

        //Register Second User
        $response = $this->json('POST', route('api.v1.auth.register'), $this->typicalUserData('user1'));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        //Test Validation Message
        $response->assertJsonValidationErrors('email', 'data.errors');

        //Check Database Count
        $this->assertDatabaseCount('users', 1);
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::query()->truncate();
        Role::query()->truncate();
        DB::statement('truncate table role_user');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedClass($className)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->seed($className);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function typicalUserData($username = 'user')
    {
        return [
            'username'      => $username,
            'password'      => bcrypt('password'),
            'email'         => 'user@user.com',
            'first_name'    => 'First',
            'last_name'     => 'Last',
            'phone'         => '01222333'
        ];
    }
}
