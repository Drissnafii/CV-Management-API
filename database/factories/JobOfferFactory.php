<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOffer>
 */
class JobOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Web Development',
            'Frontend Development',
            'Backend Development',
            'Mobile Development',
            'DevOps',
            'Data Science',
            'UI/UX Design',
            'Project Management',
            'Quality Assurance'
        ];

        $contactTypes = ['CDI', 'CDD', 'Freelance', 'Internship', 'Part-time', 'Anapec'];

        $locations = [
            'Rabat',
            'Casablanca',
            'Marrakech',
            'Fes',
            'Tangier',
            'Agadir',
            'Meknes',
            'Oujda',
            'Kenitra',
            'Tetouan',
            'El Jadida',
            'Safi',
            'Mohammedia',
            'Khouribga',
            'Beni Mellal',
            'Nador',
            'Taza',
            'Settat',
            'Berrechid',
            'Larache',
        ];

        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(3),
            'location' => $this->faker->randomElement($locations),
            'category' => $this->faker->randomElement($categories),
            'contact_type' => $this->faker->randomElement($contactTypes),
            'recruiter_id' => User::factory(),
        ];
    }
}
