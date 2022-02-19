<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Country;
use App\Models\Currency;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $company_id = $this->faker->randomElement(Branch::query()->get()->pluck(['company_id'])->toArray());
        $branch_id = $this->faker->randomElement(Branch::query()->where('company_id', $company_id)->get()->pluck(['id'])->toArray());

        return [
            'ref'           => $this->faker->unique()->word(),
            'type'          => $this->faker->randomElement(['Retail', 'Wholesale']),
            'name'          => $this->faker->name(),
            'company_name'  => $this->faker->company(),
            'phone1'        => $this->faker->phoneNumber(),
            'phone2'        => $this->faker->phoneNumber(),
            'mobile'        => $this->faker->phoneNumber(),
            'email'         => $this->faker->email(),
            'website'       => null,
            'register_no1'  => $this->faker->word(),
            'register_no2'  => $this->faker->word(),
            'address'       => $this->faker->address(),
            'custom_fields' => null,
            'company_id'    => $company_id,
            'branch_id'     => $branch_id,
            'country_id'    => $this->faker->randomElement(Country::query()->get()->pluck(['id'])->toArray()),
            'state_id'      => $this->faker->randomElement(State::query()->get()->pluck(['id'])->toArray()),
            'currency_id'   => $this->faker->randomElement(Currency::query()->get()->pluck(['id'])->toArray()),
        ];
    }
}
