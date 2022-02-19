<?php

namespace Tests\Unit;

use App\Http\Resources\v1\PlanResource;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Void_;
use Tests\TestCase;

class PlanTest extends TestCase
{
    Use RefreshDatabase, WithFaker;

    protected $connectionsToTransact = ['testing'];

    public function test_can_list_plans()
    {
        //Clear Table
        $this->truncate();

        //Create Admin
        $this->createAdmin();

        //Factory
        $plans = PlanResource::collection(Plan::factory(5)->create())->toArray(null);

        //Get Response
        $response = $this->get(route('plans.index'));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_plans = $response->json()['data'];
        $this->assertEquals($plans, $response_plans);

        //Test JSON Structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [ 'id', 'name' ],
            ]
        ]);
    }

    public function test_can_create_plan()
    {
        //Clear Table
        $this->truncate();

        //Create Admin
        $this->createAdmin();

        //Post
        $response = $this->json('POST', route('plans.store'), [
            'name'          => $this->faker->word,
            'display_name'  => $this->faker->word,
            'price_monthly' => $this->faker->numberBetween(1,100),
            'price_annual'  => $this->faker->numberBetween(1,100),
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_CREATED);

        //Check New Plan Created
        $this->assertDatabaseCount('plans', 1);
    }

    public function test_name_is_required_for_plan()
    {
        //Clear Table
        $this->truncate();

        //Create Admin
        $this->createAdmin();

        //Post
        $response = $this->json('POST', route('plans.store'), [
            'name' => '',
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        //Test Response Status
        $response->assertJsonValidationErrors('name', 'data.errors');

        //Check Plan Not Created
        $this->assertDatabaseCount('plans', 0);
    }

    public function test_can_update_plan()
    {
        //Clear Table
        $this->truncate();

        //Create Admin
        $this->createAdmin();

        //Factory
        $plan = Plan::factory()->create();

        //Update Plan
        $response = $this->json('PATCH', route('plans.update', ['plan' => $plan]), [
            'name'          => 'New Plan',
            'display_name'  => 'New Display',
            'price_monthly' => 10,
            'price_annual'  => 100,
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check New User Created
        $new_plan = Plan::query()->first();
        $this->assertEquals('New Plan', $new_plan->name);
        $this->assertEquals('New Display', $new_plan->display_name);
        $this->assertEquals(10, $new_plan->price_monthly);
        $this->assertEquals(100, $new_plan->price_annual);
    }

    public function test_can_delete_plan()
    {
        //Clear Table
        $this->truncate();

        //Create Admin
        $this->createAdmin();

        //Factory
        $plan = Plan::factory()->create();

        //Delete Plan
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $response = $this->json('DELETE', route('plans.destroy', ['plan' => $plan]));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check Database Table Count
        $this->assertDatabaseCount('plans', 0);
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Plan::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createAdmin()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);
    }
}
