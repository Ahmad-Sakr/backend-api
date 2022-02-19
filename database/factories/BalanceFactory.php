<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class BalanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'balanceable_type'  => Client::class,
            'balanceable_id'    => $this->faker->randomElement(Client::query()->get()->pluck(['id'])->toArray()),
            'currency_id'       => $this->faker->randomElement(Currency::query()->get()->pluck(['id'])->toArray()),
            'amount'            => $this->faker->numberBetween(0,10000)
        ];
    }
}
