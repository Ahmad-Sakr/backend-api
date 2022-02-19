<?php

namespace Tests\Unit;

use App\Http\Resources\v1\ClientResource;
use App\Models\Balance;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Company;
use App\Models\Currency;
use App\Models\User;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\PlansSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ClientTest extends TestCase
{
    Use RefreshDatabase, WithFaker;

    protected $connectionsToTransact = ['testing'];

    public function test_user_can_list_clients_of_his_branch()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedClass(PlansSeeder::class);

        //Create Clients + Company + Branch For Each User + Clients For Each Branch
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(2)
                    ->has(Client::factory(20))))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get Clients Of Branch
        $company = $loggedUser->companies()->first();
        $branch = $company->branches()->first();
        $clients = ClientResource::collection($branch->clients)->toArray(null);

        //Get Response
        $response = $this->get(route('branches.clients.index', $branch));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_clients = $response->json()['data'];
        $this->assertEquals($clients, $response_clients);

        //Test JSON Structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [ 'id', 'ref', 'name' ],
            ]
        ]);
    }

    public function test_user_cannot_list_clients_of_another_user_branch()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedClass(PlansSeeder::class);

        //Create Clients + Company + Branch For Each User + Clients For Each Branch
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(2)
                    ->has(Client::factory(20))))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        //Get Clients Of Branch
        $anotherCompany = $anotherUser->companies()->first();
        $anotherBranch = $anotherCompany->branches()->first();

        //Get Response
        $response = $this->get(route('branches.clients.index', $anotherBranch));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_show_single_client_of_his_branch()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedClass(PlansSeeder::class);

        //Create Company + Branch For Each User + Clients For Each Branch
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(1)))
            ->create();

        //Create One Client For Each Branch
        Client::factory(1)->create(['company_id' => 1, 'branch_id' => 1]);
        Client::factory(1)->create(['company_id' => 2, 'branch_id' => 2]);

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get Clients Of Branch
        $company = $loggedUser->companies()->first();
        $branch = $company->branches()->first();
        $client = (new ClientResource(Client::query()->where('id', 1)->first()))->toArray(null);

        //Get Response
        $response = $this->get(route('branches.clients.show', ['branch' => $branch, 'client' => 1]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Test Response Data JSON
        $response_client = $response->json()['data'];
        $this->assertEquals($client, $response_client);
    }

    public function test_user_cannot_show_single_client_of_another_user_branch()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedClass(PlansSeeder::class);

        //Create Company + Branch For Each User + Clients For Each Branch
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(1)))
            ->create();

        //Create One Client For Each Branch
        Client::factory(1)->create(['company_id' => 1, 'branch_id' => 1]);
        Client::factory(1)->create(['company_id' => 2, 'branch_id' => 2]);

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);

        //Get Response
        $response = $this->get(route('branches.clients.show', ['branch' => 2, 'client' => 2]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Get Response
        $response = $this->get(route('branches.clients.show', ['branch' => 1, 'client' => 2]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_create_new_clients_in_his_branch()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedClass(PlansSeeder::class);

        //Seed Currencies
        $this->seedClass(CurrencySeeder::class);

        //Create Clients + Company + Branch For Each User
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(2)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);
        $company = $loggedUser->companies()->first();
        $branch = $company->branches()->first();

        //Post
        $response = $this->json('POST', route('branches.clients.store', $branch), [
            'data' => [
                [
                    'ref'       => '01',
                    'name'      => 'Client 01',
                    'balances'  => [
                        [
                            'currency_id'   => $this->faker->randomElement(Currency::query()->get()->pluck(['id'])->toArray()),
                            'amount'        => $this->faker->numberBetween(0,10000)
                        ]
                    ]
                ],
                [
                    'ref'   => '02',
                    'name'  => 'Client 02',
                    'balances'  => [
                        [
                            'currency_id'   => $this->faker->randomElement(Currency::query()->get()->pluck(['id'])->toArray()),
                            'amount'        => $this->faker->numberBetween(0,10000)
                        ]
                    ]
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check Clients Count
        $this->assertDatabaseCount('clients', 2);

        //Check Balances of New Clients
        Client::query()->get()->each(function ($client) {
            $this->assertEquals(1, $client->balances()->count());
        });

        //Check Balances Count
        $this->assertDatabaseCount('balances', 2);
    }

    public function test_user_cannot_create_new_clients_in_another_user_branch()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedClass(PlansSeeder::class);

        //Create Clients + Company + Branch For Each User
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(2)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        $anotherCompany = $anotherUser->companies()->first();
        $anotherBranch = $anotherCompany->branches()->first();

        //Post
        $response = $this->json('POST', route('branches.clients.store', $anotherBranch), [
            'data' => [
                [
                    'ref'   => '01',
                    'name'  => 'Client 01'
                ],
                [
                    'ref'   => '02',
                    'name'  => 'Client 02'
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check Clients Count
        $this->assertDatabaseCount('clients', 0);
    }

    public function test_user_can_update_existing_clients_in_his_branch()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedClass(PlansSeeder::class);

        //Seed Currencies
        $this->seedClass(CurrencySeeder::class);

        //Create Clients + Company + Branch For Each User
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(2)))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);
        $company = $loggedUser->companies()->first();
        $branch = $company->branches()->first();

        //Post Save First Client
        $this->json('POST', route('branches.clients.store', $branch), [
            'data' => [
                [
                    'ref'       => '01',
                    'name'      => 'Client 01',
                    'balances'  => [
                        [
                            'currency_id'   => $this->faker->randomElement(Currency::query()->get()->pluck(['id'])->toArray()),
                            'amount'        => $this->faker->numberBetween(0,10000)
                        ]
                    ]
                ]
            ],
        ]);

        //Post Update First Client
        $response = $this->json('POST', route('branches.clients.store', $branch), [
            'data' => [
                [
                    'ref'       => '01',
                    'name'      => 'New Client 01',
                    'balances'  => [
                        [
                            'currency_id'   => $this->faker->randomElement(Currency::query()->get()->pluck(['id'])->toArray()),
                            'amount'        => 100
                        ],
                        [
                            'currency_id'   => $this->faker->randomElement(Currency::query()->get()->pluck(['id'])->toArray()),
                            'amount'        => 100
                        ]
                    ]
                ]
            ],
        ]);

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check New Client Created
        $this->assertDatabaseCount('clients', 1);

        //Check New Client Name
        $this->assertEquals('New Client 01', Client::query()->first()->name);

        //Check Balances of New Clients
        Client::query()->get()->each(function ($client) {
            $this->assertEquals(2, $client->balances()->count());
            $client->balances->each(function ($balance) {
                $this->assertEquals(100, $balance->amount);
            });
        });

        //Check Balances Count
        $this->assertDatabaseCount('balances', 2);
    }

    public function test_user_can_delete_client_in_his_branch()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedClass(PlansSeeder::class);

        //Seed Currencies
        $this->seedClass(CurrencySeeder::class);

        //Create Clients + Company + Branch For Each User + Clients For Each Branch
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(1)
                    ->has(Client::factory(10))))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $this->actingAs($loggedUser);
        $company = $loggedUser->companies()->first();
        $branch = $company->branches()->first();

        //Get First Client
        $client = $branch->clients->first();

        //Add Balances For Client
        $client->balances()->createMany([
            [
                'currency_id'   => $this->faker->randomElement(Currency::query()->get()->pluck(['id'])->toArray()),
                'amount'        => $this->faker->numberBetween(0,10000)
            ],
            [
                'currency_id'   => $this->faker->randomElement(Currency::query()->get()->pluck(['id'])->toArray()),
                'amount'        => $this->faker->numberBetween(0,10000)
            ]
        ]);

        //Post
        $response = $this->json('DELETE', route('branches.clients.destroy', ['branch' => $branch, 'client' => $client]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_OK);

        //Check Clients Count
        $this->assertDatabaseCount('clients', 19);

        //Check Clients Count Of Logged In User
        $this->assertEquals(9, $branch->clients()->count());

        //Check Balances Count
        $this->assertDatabaseCount('balances', 0);
    }

    public function test_user_cannot_delete_client_in_another_user_branch()
    {
        //Truncate
        $this->truncate();

        //Seed Plans
        $this->seedClass(PlansSeeder::class);

        //Create Clients + Company + Branch For Each User + Clients For Each Branch
        $users = User::factory(2)
            ->has(Company::factory(1)
                ->has(Branch::factory(1)
                    ->has(Client::factory(10))))
            ->create();

        //Log in With First User
        $loggedUser = $users[0];
        $anotherUser = $users[1];
        $this->actingAs($loggedUser);

        $anotherCompany = $anotherUser->companies()->first();
        $anotherBranch = $anotherCompany->branches()->first();

        //Get First Client
        $client = $anotherBranch->clients->first();

        //Post
        $response = $this->json('DELETE', route('branches.clients.destroy', ['branch' => $anotherBranch, 'client' => $client]));

        //Test Response Status
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //Check Check Clients Count
        $this->assertDatabaseCount('clients', 20);
    }

    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::query()->truncate();
        Company::query()->truncate();
        Branch::query()->truncate();
        Client::query()->truncate();
        Balance::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedClass($className)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->seed($className);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
