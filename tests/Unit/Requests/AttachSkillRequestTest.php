<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\AttachSkillRequest;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class AttachSkillRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test validation rules for AttachSkillRequest.
     *
     * @return void
     */
    public function test_validation_rules()
    {
        $request = new AttachSkillRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('skill_id', $rules);
        $this->assertEquals('required|exists:skills,id', $rules['skill_id']);
    }

    /**
     * Test authorization logic for AttachSkillRequest.
     *
     * @return void
     */
    public function test_authorize()
    {
        // Create a user with appropriate role (assuming 'admin' or 'recruiter' can manage skills)
        $user = User::factory()->createOne();

        // Assign role to user (this depends on your role implementation)
        // For example: $user->assignRole('admin');

        // Set the authenticated user
        $this->actingAs($user);

        $request = new AttachSkillRequest();

        // The authorize method should return true for users with appropriate permissions
        $this->assertTrue($request->authorize());
    }
}
