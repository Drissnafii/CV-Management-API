<?php

namespace Database\Factories;


use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['B', 'P', 'V']);
        return [
            'costumer_id' => CostumerFactory::factory(),
            'amount' => fake()->numberBetween(100, 200),
            'status' => $status,
            'billed_data' => fake()->dateTimeThisDecade(),
            'paid_date' => $status == 'P' ? fake()->dateTimeThisDecade() : NULL
        ];
    }
}
