<?php

namespace Database\Factories;

use App\Models\ReportTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = ReportTransaction::class;
    public function definition()
    {
        $dateTime = now();
        return [
            'dates' => $this->faker->dateTimeBetween('2022-01-01', '2022-12-30'),
            'merchant_id' => rand(1,5),
            'method_id' => rand(1,5),
            'gateway_id' => rand(1,8),
            'bank_code' => $this->faker->randomElement(['STB', 'VCB', 'VPB', 'BIDV', 'MB']),
            'channel' => $this->faker->randomElement(['ecom', 'invoice']),
            'trans_status' => rand(1,5),
            'total_amount' => $this->faker->numberBetween(18923, 9999999),
            'card_id'=>rand(1,20),
            'created_at'=>$this->faker->dateTimeBetween('2022-01-01', '2022-12-30')
        ];
    }
}
