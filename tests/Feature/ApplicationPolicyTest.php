<?php

namespace Tests\Unit\Policies;

use App\Models\JobApplication;
use App\Models\User;
use App\Policies\ApplicationPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationPolicyTest extends TestCase
{
    use RefreshDatabase; // If you need database interactions

    protected ApplicationPolicy $policy;
    protected User $user;
    protected JobApplication $jobApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ApplicationPolicy();
        $this->user = User::factory()->create();
        $this->jobApplication = JobApplication::factory()->create(['user_id' => $this->user->id, 'status' => 'pending']);
    }

    public function test_viewAny_policy_returns_false()
    {
        $this->assertFalse($this->policy->viewAny($this->user));
    }

    public function test_view_policy_allows_user_to_view_their_own_application()
    {
        $this->assertTrue($this->policy->view($this->user, $this->jobApplication));
    }

    public function test_view_policy_denies_user_to_view_others_application()
    {
        $otherUser = User::factory()->create();
        $otherApplication = JobApplication::factory()->create(['user_id' => $otherUser->id]);
        $this->assertFalse($this->policy->view($this->user, $otherApplication));
    }

    public function test_create_policy_allows_user_to_create_application_for_themselves() // Note: Your policy logic here
    {
        // In your policy, create checks if user_id matches in the argument (which is unusual for create)
        // Assuming you meant to check if the user CAN create an application in general
        $this->assertTrue($this->policy->create($this->user, new JobApplication(['user_id' => $this->user->id]))); // Pass a new JobApplication instance
    }

    public function test_update_policy_allows_user_to_update_pending_application()
    {
        $this->assertTrue($this->policy->update($this->user, $this->jobApplication));
    }

    public function test_update_policy_denies_user_to_update_not_pending_application()
    {
        $this->jobApplication->status = 'approved';
        $this->assertFalse($this->policy->update($this->user, $this->jobApplication));
    }

    public function test_update_policy_denies_user_to_update_others_application()
    {
        $otherUser = User::factory()->create();
        $otherApplication = JobApplication::factory()->create(['user_id' => $otherUser->id, 'status' => 'pending']);
        $this->assertFalse($this->policy->update($this->user, $otherApplication));
    }

    public function test_delete_policy_allows_user_to_delete_pending_application()
    {
        $this->assertTrue($this->policy->delete($this->user, $this->jobApplication));
    }

    public function test_delete_policy_denies_user_to_delete_not_pending_application()
    {
        $this->jobApplication->status = 'approved';
        $this->assertFalse($this->policy->delete($this->user, $this->jobApplication));
    }

    public function test_delete_policy_denies_user_to_delete_others_application()
    {
        $otherUser = User::factory()->create();
        $otherApplication = JobApplication::factory()->create(['user_id' => $otherUser->id, 'status' => 'pending']);
        $this->assertFalse($this->policy->delete($this->user, $otherApplication));
    }

    public function test_restore_policy_returns_false()
    {
        $this->assertFalse($this->policy->restore($this->user, $this->jobApplication));
    }

    public function test_forceDelete_policy_returns_false()
    {
        $this->assertFalse($this->policy->forceDelete($this->user, $this->jobApplication));
    }
}
