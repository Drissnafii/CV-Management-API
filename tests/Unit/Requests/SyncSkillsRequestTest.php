<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\SyncSkillsRequest;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncSkillsRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test validation rules for SyncSkillsRequest.
     *
     * @return void
     */
    public function test_validation_rules()
    {
        $request = new SyncSkillsRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('skills', $rules);
        $this->assertEquals('required|array', $rules['skills']);
        $this->assertArrayHasKey('skills.*', $rules);
        $this->assertEquals('required|exists:skills,id', $rules['skills.*']);
    }


    /**
     * Test authorization logic for SyncSkillsRequest.
     *
     * @return void
     */
    public function test_authorize()
    {
        // Create a user with appropriate role
        $user = User::factory()->create();

        // Assign role to user (this depends on your role implementation)
        // For example: $user->assignRole('admin');

        // Set the authenticated user
        $this->actingAs($user);

        $request = new SyncSkillsRequest();

        // The authorize method should return true for users with appropriate permissions
        $this->assertTrue($request->authorize());
    }
}
