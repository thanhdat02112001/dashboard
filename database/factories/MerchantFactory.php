<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MerchantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->city(),
            'maDN' => $this->faker->regexify('[A-Z]{5}[0-4]{3}'),
            'status' => $this->faker->boolean(),
            'created_at' => now(),
        ];
    }
}
