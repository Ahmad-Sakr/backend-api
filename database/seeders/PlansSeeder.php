<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::query()->truncate();

        Plan::query()->create([
            'name'          => 'basic',
            'display_name'  => 'Basic Plan',
            'price_monthly' => 12,
            'price_annual'  => 120,
        ]);

        Plan::query()->create([
            'name'          => 'advanced',
            'display_name'  => 'Advanced Plan',
            'price_monthly' => 20,
            'price_annual'  => 210,
        ]);

        Plan::query()->create([
            'name'          => 'business',
            'display_name'  => 'Business Plan',
            'price_monthly' => 25,
            'price_annual'  => 250,
        ]);

        Plan::query()->create([
            'name'          => 'enterprise',
            'display_name'  => 'Enterprise Plan',
            'price_monthly' => 50,
            'price_annual'  => 520,
        ]);
    }
}
