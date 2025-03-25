<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\DetachSkillRequest;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DetachSkillRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test validation rules for DetachSkillRequest.
     *
     * @return void
     */
    public function test_validation_rules()
    {
        $request = new DetachSkillRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('skill_id', $rules);
        $this->assertEquals('required|exists:skills,id', $rules['skill_id']);
    }

    /**
     * Test authorization logic for DetachSkillRequest.
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

        $request = new DetachSkillRequest();

        // The authorize method should return true for users with appropriate permissions
        $this->assertTrue($request->authorize());
    }
}
