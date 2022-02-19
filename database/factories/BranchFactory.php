<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->company();
        return [
            'name'          => $name,
            'slug'          => Str::slug($name),
            'display_name'  => $name,
            'email'         => $this->faker->safeEmail(),
            'phone1'        => $this->faker->phoneNumber(),
            'phone2'        => $this->faker->phoneNumber(),
            'company_id'    => $this->faker->randomElement(Company::query()->get()->pluck(['id'])->toArray())
        ];
    }
}
