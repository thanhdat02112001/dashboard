<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Card::class;
    public function definition()
    {
        return [
            'card_no' => $this->faker->numberBetween(102148378, 921877322),
            'card_brand' =>rand(1,8),
            'created_at' => now()
        ];
    }
}
