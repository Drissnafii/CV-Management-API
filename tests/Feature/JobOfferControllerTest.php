<?php

namespace Tests\Feature\Api;

use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobOfferControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;


    public function test_index_returns_paginated_job_offers()
    {
        // Create some job offers
        JobOffer::factory()->count(15)->create();

        // Make request to index endpoint
        $response = $this->getJson('/api/job-offers');

        // Assert response
        $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'data' => [
                'current_page',
                'data',
                'per_page',
                'total'
                ]
                ])
                ->assertJson([
                    'status' => 'success'
                ]);

                // Check we have 10 items per page (as defined in controller)
                $this->assertCount(10, $response->json('data.data'));
            }
            protected function setUp(): void
            {
                parent::setUp();
                $this->user = User::factory()->create();
                // Create a token for the user
                $token = $this->user->createToken('test-token')->plainTextToken;
                // Set the token in the headers
                $this->withHeader('Authorization', 'Bearer ' . $token);
            }

    public function test_store_creates_new_job_offer()
    {
        // Acting as authenticated user (recruiter)
        $response = $this->actingAs($this->user)
            ->postJson('/api/job-offers', [
                'title' => 'Software Developer',
                'description' => 'A great job opportunity',
                'location' => 'Remote',
                'category' => 'IT',
                'contact_type' => 'Full-time',
            ]);

        // Assert response
        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Job offer created successfully',
                'data' => [
                    'title' => 'Software Developer',
                    'description' => 'A great job opportunity',
                    'location' => 'Remote',
                    'category' => 'IT',
                    'contact_type' => 'Full-time',
                    'recruiter_id' => $this->user->id
                ]
            ]);

        // Assert the job offer was stored in the database
        $this->assertDatabaseHas('job_offers', [
            'title' => 'Software Developer',
            'recruiter_id' => $this->user->id
        ]);
    }

    public function test_store_validates_input()
    {
        // Acting as authenticated user but with invalid data
        $response = $this->actingAs($this->user)
            ->postJson('/api/job-offers', [
                // Missing required fields
                'title' => 'Software Developer',
                // Other fields missing
            ]);

        // Assert validation error
        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'errors'
            ]);
    }

    public function test_show_returns_specific_job_offer()
    {
        // Create a job offer
        $jobOffer = JobOffer::factory()->create();

        // Make request to show endpoint
        $response = $this->getJson("/api/job-offers/{$jobOffer->id}");

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'job exist 😊👌',
                'data' => [
                    'id' => $jobOffer->id,
                    'title' => $jobOffer->title
                ]
            ]);
    }

    public function test_show_returns_404_for_nonexistent_job()
    {
        // Make request with non-existent ID
        $response = $this->getJson('/api/job-offers/999');

        // Assert 404 response
        $response->assertStatus(404);
    }

    public function test_update_modifies_existing_job_offer()
    {
        // Create a job offer
        $jobOffer = JobOffer::factory()->create([
            'recruiter_id' => $this->user->id
        ]);

        // Acting as authenticated user
        $response = $this->actingAs($this->user)
            ->putJson("/api/job-offers/{$jobOffer->id}", [
                'title' => 'Updated Job Title',
                'description' => 'Updated description',
                'location' => 'New York',
                'category' => 'Finance',
                'contact_type' => 'Part-time',
            ]);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Job offer updated successfully',
                'data' => [
                    'id' => $jobOffer->id,
                    'title' => 'Updated Job Title',
                    'description' => 'Updated description',
                    'location' => 'New York',
                    'category' => 'Finance',
                    'contact_type' => 'Part-time',
                ]
            ]);

        // Assert the job offer was updated in the database
        $this->assertDatabaseHas('job_offers', [
            'id' => $jobOffer->id,
            'title' => 'Updated Job Title'
        ]);
    }

    public function test_update_validates_input()
    {
        // Create a job offer
        $jobOffer = JobOffer::factory()->create([
            'recruiter_id' => $this->user->id
        ]);

        // Acting as authenticated user but with invalid data
        $response = $this->actingAs($this->user)
            ->putJson("/api/job-offers/{$jobOffer->id}", [
                'title' => '', // Empty title should fail validation
                'description' => 'Updated description',
                'location' => 'New York',
                'category' => 'Finance',
                'contact_type' => 'Part-time',
            ]);

        // Assert validation error
        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'errors'
            ]);
    }

    public function test_destroy_deletes_job_offer()
    {
        // Create a job offer
        $jobOffer = JobOffer::factory()->create([
            'recruiter_id' => $this->user->id
        ]);

        // Acting as authenticated user
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/job-offers/{$jobOffer->id}");

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Job offer delete successfully'
            ]);

        // Assert the job offer was deleted from the database
        $this->assertDatabaseMissing('job_offers', [
            'id' => $jobOffer->id
        ]);
    }

    public function test_destroy_returns_404_for_nonexistent_job()
    {
        // Acting as authenticated user
        $response = $this->actingAs($this->user)
            ->deleteJson('/api/job-offers/999');

        // Assert 404 response
        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Job offer not found'
            ]);
    }
}
