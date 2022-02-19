<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->truncate();

        Role::query()->create(['name' => 'Admin']);
        Role::query()->create(['name' => 'User']);
        Role::query()->create(['name' => 'Manager']);
        Role::query()->create(['name' => 'Accountant']);
    }
}
