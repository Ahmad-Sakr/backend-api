<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ref'           => $this->faker->countryCode(),
            'name'          => $this->faker->country(),
            'company_id'    => $this->faker->randomElement(Company::query()->get()->pluck(['id'])->toArray())
        ];
    }
}
