<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'          => $this->faker->unique()->word(),
            'display_name'  => $this->faker->word(),
            'price_monthly' => $this->faker->numberBetween(4,125),
            'price_annual'  => $this->faker->numberBetween(40,400),
        ];
    }
}
