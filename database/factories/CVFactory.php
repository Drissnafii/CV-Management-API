<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CV>
 */
class CVFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        return [
            'user_id' => $user->id,
            'title' => $this->faker->sentence,
            'file_path' => 'cvs/' . $user->id . '/' . $this->faker->uuid . '.pdf',
            'file_name' => $this->faker->uuid . '.pdf',
            'file_size' => $this->faker->numberBetween(1000, 5000),
        ];
    }
}
