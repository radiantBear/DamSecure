<?php

namespace Tests\Feature;

use App\Models;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DataTest extends TestCase
{
    use RefreshDatabase;


    public function test_data_insertion_succeeds_with_valid_token(): void
    {
        $project = Models\Project::factory()->create();
        $token = $project->createToken('test_token');

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->postJson('/api/data', ['id' => 5, 'user' => 'john']);

        $response->assertStatus(201);
        $this->assertDatabaseHas('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_update_succeeds_with_valid_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token');

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->putJson('/api/data/' . $data->id, ['id' => 5, 'user' => 'john']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_deletion_succeeds_with_valid_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token');

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->deleteJson('/api/data/' . $data->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
    }


    public function test_data_insertion_fails_without_token(): void
    {
        $project = Models\Project::factory()->create();

        $response = $this
            ->postJson('/api/data', ['id' => 5, 'user' => 'john']);

        $response->assertStatus(401);
        $this->assertDatabaseMissing('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_insertion_fails_with_invalid_token(): void
    {
        $project = Models\Project::factory()->create();
        $project->createToken('test_token');

        $response = $this
            ->withHeader('Authorization', 'Bearer s0meInv4l1dT0k3nW1th48Ch4r4ct3rs3456789012345678')
            ->postJson('/api/data', ['id' => 5, 'user' => 'john']);

        $response->assertStatus(401);
        $this->assertDatabaseMissing('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_insertion_fails_with_revoked_token(): void
    {
        $project = Models\Project::factory()->create();
        $token = $project->createToken('test_token');
        $token->accessToken->delete();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->postJson('/api/data', ['id' => 5, 'user' => 'john']);

        $response->assertStatus(401);
        $this->assertDatabaseMissing('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_update_fails_without_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);

        $response = $this
            ->putJson('/api/data/' . $data->id, ['id' => 5, 'user' => 'john']);

        $response->assertStatus(401);
        $this->assertDatabaseHas('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
        $this->assertDatabaseMissing('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_update_fails_with_invalid_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);

        $other_project = Models\Project::factory()->create();
        $other_token = $other_project->createToken('test_token');

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $other_token->plainTextToken)
            ->putJson('/api/data/' . $data->id, ['id' => 5, 'user' => 'john']);

        $response->assertStatus(403);
        $this->assertDatabaseHas('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
        $this->assertDatabaseMissing('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_update_fails_with_revoked_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token');
        $token->accessToken->delete();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->putJson('/api/data/' . $data->id, ['id' => 5, 'user' => 'john']);

        $response->assertStatus(401);
        $this->assertDatabaseHas('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
        $this->assertDatabaseMissing('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_deletion_fails_without_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);

        $response = $this
            ->deleteJson('/api/data/' . $data->id);

        $response->assertStatus(401);
        $this->assertDatabaseHas('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
    }


    public function test_data_deletion_fails_with_invalid_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);

        $other_project = Models\Project::factory()->create();
        $other_token = $other_project->createToken('test_token');

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $other_token->plainTextToken)
            ->deleteJson('/api/data/' . $data->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
    }


    public function test_data_deletion_fails_with_revoked_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token');
        $token->accessToken->delete();

        $response = $this
        ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
        ->deleteJson('/api/data/' . $data->id);
        
        $response->assertStatus(401);
        $this->assertDatabaseHas('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
    }
}
