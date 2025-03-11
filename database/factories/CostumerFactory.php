<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Costumer>
 */
class CostumerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['I', 'B']);
        $name = $type == 'I' ? $this->faker->name()  : $this->faker->company();
        $status = fake()->randomElement(['B', 'P', 'V']);


        return [
            'name'=> $name,
            'type' => $type,
            'email' => fake()->email(),
            'adress' => fake()->streetAddress(),
            'city' => fake()->city(),
            'status' => fake()->name(),
            'postal_code' => fake()->postcode()
        ];
    }
}
