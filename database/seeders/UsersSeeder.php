<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->truncate();

        $user = User::query()->create([
            'first_name'    => 'Ahmad',
            'last_name'     => 'Sakr',
            'username'      => 'admin',
            'email'         => 'sakr-ahmad@hotmail.com',
            'password'      => bcrypt('123456'),
            'phone'         => '+249962536765',
        ]);
        $user->roles()->attach(1);

        User::factory(10)
            ->create()
            ->each(function($user, $id) {
                $user->roles()->attach(3);
                $user->update([
                   'username' => 'user' . ($id + 1)
                ]);
            });
    }
}
