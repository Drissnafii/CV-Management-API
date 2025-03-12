<?php

namespace Database\Seeders;

use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recruiter = User::firstOrCreate(
            ['email' => 'recruiter@example.com'],
            [
                'name' => 'Test Recruiter',
                'password' => bcrypt('recruter123'),
            ]
            );

        JobOffer::factory()
        ->count(15)
        ->for($recruiter, 'recruiter')
        ->create();

        JobOffer::factory()
        ->count(5)
        ->for($recruiter, 'recruiter')
        ->create();

    }
}
