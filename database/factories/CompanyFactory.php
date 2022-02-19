<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
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
            'app_name'      => 'Brain ERP',
            'business_type' => $this->faker->word(),
            'email'         => $this->faker->safeEmail(),
            'phone1'        => $this->faker->phoneNumber(),
            'phone2'        => $this->faker->phoneNumber(),
            'user_id'       => $this->faker->randomElement(User::query()->where('id', '>', 1)->get()->pluck(['id'])->toArray())
        ];
    }
}
