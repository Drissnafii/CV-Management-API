<?php

namespace Tests\Feature;

use App\Models\CV;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class CVControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function testStoreCv()
    {
        // Debugging statements
        // dd(DB::connection()->getDatabaseName(), Schema::hasTable('cvs')); 

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->create('test.pdf');

        $response = $this->postJson('/api/cvs', [
            'title' => 'Test CV',
            'cv_file' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('cvs', [
            'user_id' => $user->id,
            'title' => 'Test CV',
        ]);

        $this->assertTrue(Storage::disk('local')->exists("cvs/{$user->id}/" . $file->hashName()));
    }


    public function testShowCv()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $cv = CV::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/cvs/{$cv->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);
    }

    public function testShowCvUnauthorized()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user2);

        $cv = CV::factory()->create(['user_id' => $user1->id]);

        $response = $this->getJson("/api/cvs/{$cv->id}");

        $response->assertStatus(403)
            ->assertJson(['status' => 'error']);
    }

    public function testDestroyCv()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $cv = CV::factory()->create(['user_id' => $user->id]);
        Storage::disk('local')->put($cv->file_path, 'test content');

        $response = $this->deleteJson("/api/cvs/{$cv->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseMissing('cvs', ['id' => $cv->id]);
        $this->assertFalse(Storage::disk('local')->exists($cv->file_path));
    }

    public function testDestroyCvUnauthorized()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user2);

        $cv = CV::factory()->create(['user_id' => $user1->id]);

        $response = $this->deleteJson("/api/cvs/{$cv->id}");

        $response->assertStatus(403)
            ->assertJson(['status' => 'error']);
    }

    public function testDownloadCv()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $cv = CV::factory()->create(['user_id' => $user->id]);
        Storage::disk('local')->put($cv->file_path, 'test content');

        $response = $this->getJson("/api/cvs/{$cv->id}/download");

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $downloadUrl = $response->json('download_url');

        $downloadResponse = $this->get($downloadUrl);
        $downloadResponse->assertStatus(200);
    }

    public function testDownloadCvUnauthorized()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user2);

        $cv = CV::factory()->create(['user_id' => $user1->id]);

        $response = $this->getJson("/api/cvs/{$cv->id}/download");

        $response->assertStatus(403)
            ->assertJson(['status' => 'error']);
    }
}
