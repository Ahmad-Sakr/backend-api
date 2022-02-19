<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ref'           => $this->faker->text(5),
            'name'          => $this->faker->word(),
            'company_id'    => $this->faker->randomElement(Company::query()->get()->pluck(['id'])->toArray())
        ];
    }
}
