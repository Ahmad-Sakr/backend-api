<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call(RolesSeeder::class);
        $this->call(PlansSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(BranchSeeder::class);

        $this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(BalanceSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
