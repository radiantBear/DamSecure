<?php

namespace Tests\Feature;

use App\Models;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TestDataTest extends TestCase
{
    use RefreshDatabase;


    public function test_data_retrieval_succeeds_with_valid_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\TestData::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['download']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->getJson('/api/data/test');

        $response->assertOk();
        $response->assertSee($data->data, false);
    }


    public function test_data_update_succeeds_for_contributor(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\TestData::factory()->create(['project_id' => $project->id]);
        $user = Models\User::factory()->create();
        Models\ProjectUser::factory()->create(['project_id' => $project->id, 'user_id' => $user->id, 'role' => 'contributor']);

        $response = $this
            ->actingAs($user)
            ->put('/data/test/' . $data->id, ['data' => '{"id": 5, "user": "john"}']);

        $response->assertRedirect("/projects/{$project->uuid}");
        $this->assertDatabaseHas('test_data', [
            'data' => '{"id": 5, "user": "john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_retrieval_fails_without_token(): void
    {
        $project = Models\Project::factory()->create();
        Models\TestData::factory()->create(['project_id' => $project->id]);

        $response = $this->getJson('/api/data/test');

        $response->assertUnauthorized();
        $response->assertContent('{"message":"Unauthenticated."}');
    }


    public function test_data_retrieval_fails_with_invalid_token(): void
    {
        $project = Models\Project::factory()->create();
        Models\TestData::factory()->create(['project_id' => $project->id]);

        $response = $this
            ->withHeader('Authorization', 'Bearer s0meInv4l1dT0k3nW1th48Ch4r4ct3rs3456789012345678')
            ->getJson('/api/data/test');

        $response->assertUnauthorized();
        $response->assertContent('{"message":"Unauthenticated."}');
    }


    public function test_data_retrieval_fails_with_revoked_token(): void
    {
        $project = Models\Project::factory()->create();
        Models\TestData::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['download']);
        $token->accessToken->delete();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->getJson('/api/data/test');

        $response->assertUnauthorized();
        $response->assertContent('{"message":"Unauthenticated."}');
    }


    public function test_data_retrieval_fails_with_upload_token(): void
    {
        $project = Models\Project::factory()->create();
        Models\TestData::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['upload']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->getJson('/api/data/test');

        $response->assertForbidden();
    }


    public function test_unauthenticated_user_cannot_update_data(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\TestData::factory()->create(['project_id' => $project->id]);

        $response = $this
            ->put('/data/test/' . $data->id, ['data' => '{"id": 5, "user": "john"}']);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('test_data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
        $this->assertDatabaseMissing('test_data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_user_cannot_update_other_project_data(): void
    {
        $user = Models\User::factory()->create();
    
        $otherUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        $data = Models\TestData::factory()->create(['project_id' => $project->id]);
        Models\ProjectUser::factory()->create(['project_id' => $project->id, 'user_id' => $otherUser->id]);

        $response = $this
            ->actingAs($user)
            ->put('/data/test/' . $data->id, ['data' => '{"id": 5, "user": "john"}']);

        $response->assertForbidden();
        $this->assertDatabaseHas('test_data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
        $this->assertDatabaseMissing('test_data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }
}
