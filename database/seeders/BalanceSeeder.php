<?php

namespace Database\Seeders;

use App\Models\Balance;
use Illuminate\Database\Seeder;

class BalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Balance::query()->truncate();

        Balance::factory(100)->create();
    }
}
