<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\SkillController;
use App\Http\Requests\AttachSkillRequest;
use App\Http\Requests\DetachSkillRequest;
use App\Http\Requests\SyncSkillsRequest;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SkillControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $skill;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user and skill for use in tests
        $this->user = User::factory()->create();
        $this->skill = Skill::factory()->create();
    }

    /**
     * Test attaching a skill to a user.
     *
     * @return void
     */
    public function test_attach_skill_to_user()
    {
        // Create a partial mock of AttachSkillRequest
        $request = $this->createPartialMock(AttachSkillRequest::class, ['validated']);
        $request->expects($this->any())
            ->method('validated')
            ->willReturn(['skill_id' => $this->skill->id]);

        // Create controller instance
        $controller = new SkillController();

        // Call the method
        $response = $controller->attachSkill($request, $this->user);

        // Assert the skill was attached
        $this->assertDatabaseHas('skill_user', [
            'user_id' => $this->user->id,
            'skill_id' => $this->skill->id,
        ]);

        // Assert the response is correct
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Compétence ajoutée avec succès', $responseData['message']);
        $this->assertArrayHasKey('user', $responseData);
    }

    /**
     * Test detaching a skill from a user.
     *
     * @return void
     */
    public function test_detach_skill_from_user()
    {
        // Attach the skill first
        $this->user->skills()->attach($this->skill->id);

        // Verify the skill is attached
        $this->assertDatabaseHas('skill_user', [
            'user_id' => $this->user->id,
            'skill_id' => $this->skill->id,
        ]);

        // Create a partial mock of DetachSkillRequest
        $request = $this->createPartialMock(DetachSkillRequest::class, ['validated']);
        $request->expects($this->any())
            ->method('validated')
            ->willReturn(['skill_id' => $this->skill->id]);

        // Create controller instance
        $controller = new SkillController();

        // Call the method
        $response = $controller->detachSkill($request, $this->user);

        // Assert the skill was detached
        $this->assertDatabaseMissing('skill_user', [
            'user_id' => $this->user->id,
            'skill_id' => $this->skill->id,
        ]);

        // Assert the response is correct
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Compétence supprimée avec succès', $responseData['message']);
        $this->assertArrayHasKey('user', $responseData);
    }

    /**
     * Test syncing skills for a user.
     *
     * @return void
     */
    public function test_sync_skills_for_user()
    {
        // Create additional skills
        $skill2 = Skill::factory()->create();
        $skill3 = Skill::factory()->create();

        // Attach one skill initially
        $this->user->skills()->attach($this->skill->id);

        // Create a partial mock of SyncSkillsRequest
        $request = $this->createPartialMock(SyncSkillsRequest::class, ['validated']);
        $request->expects($this->any())
            ->method('validated')
            ->willReturn(['skills' => [$skill2->id, $skill3->id]]);

        // Create controller instance
        $controller = new SkillController();

        // Call the method
        $response = $controller->syncSkills($request, $this->user);

        // Assert the skills were synced correctly
        $this->assertDatabaseMissing('skill_user', [
            'user_id' => $this->user->id,
            'skill_id' => $this->skill->id,
        ]);
        $this->assertDatabaseHas('skill_user', [
            'user_id' => $this->user->id,
            'skill_id' => $skill2->id,
        ]);
        $this->assertDatabaseHas('skill_user', [
            'user_id' => $this->user->id,
            'skill_id' => $skill3->id,
        ]);

        // Assert the response is correct
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Compétences synchronisées avec succès', $responseData['message']);
        $this->assertArrayHasKey('user', $responseData);
    }

    /**
     * Test attaching a skill that is already attached.
     *
     * @return void
     */
    public function test_attach_already_attached_skill()
    {
        // Attach the skill first
        $this->user->skills()->attach($this->skill->id);

        // Create a partial mock of AttachSkillRequest
        $request = $this->createPartialMock(AttachSkillRequest::class, ['validated']);
        $request->expects($this->any())
            ->method('validated')
            ->willReturn(['skill_id' => $this->skill->id]);

        // Create controller instance
        $controller = new SkillController();

        // Call the method
        $response = $controller->attachSkill($request, $this->user);

        // Assert the response is correct
        $this->assertEquals(200, $response->getStatusCode());

        // Check that the skill is still attached (only once)
        $this->assertEquals(1, $this->user->skills()->where('skill_id', $this->skill->id)->count());
    }

    /**
     * Test detaching a skill that is not attached.
     *
     * @return void
     */
    public function test_detach_not_attached_skill()
    {
        // Make sure the skill is not attached
        $this->user->skills()->detach($this->skill->id);

        // Create a partial mock of DetachSkillRequest
        $request = $this->createPartialMock(DetachSkillRequest::class, ['validated']);
        $request->expects($this->any())
            ->method('validated')
            ->willReturn(['skill_id' => $this->skill->id]);

        // Create controller instance
        $controller = new SkillController();

        // Call the method
        $response = $controller->detachSkill($request, $this->user);

        // Assert the response is correct
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test syncing with an empty skills array.
     *
     * @return void
     */
    public function test_sync_with_empty_skills_array()
    {
        // Attach some skills first
        $this->user->skills()->attach($this->skill->id);
        $skill2 = Skill::factory()->create();
        $this->user->skills()->attach($skill2->id);

        // Create a partial mock of SyncSkillsRequest
        $request = $this->createPartialMock(SyncSkillsRequest::class, ['validated']);
        $request->expects($this->any())
            ->method('validated')
            ->willReturn(['skills' => []]);

        // Create controller instance
        $controller = new SkillController();

        // Call the method
        $response = $controller->syncSkills($request, $this->user);

        // Assert all skills were removed
        $this->assertEquals(0, $this->user->skills()->count());

        // Assert the response is correct
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Compétences synchronisées avec succès', $responseData['message']);
    }
}
