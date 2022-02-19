<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ref'           => $this->faker->currencyCode(),
            'rate'          => $this->faker->numberBetween(150,16000),
            'company_id'    => $this->faker->randomElement(Company::query()->get()->pluck(['id'])->toArray())
        ];
    }
}
