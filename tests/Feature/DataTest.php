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


    public function test_data_retrieval_succeeds_with_valid_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory(20)->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['download']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->getJson('/api/data');

        $response->assertOk();
        $response->assertJsonCount(20);
        foreach ($data as $model) {
            $response->assertJsonFragment($model->toArray());
        }
    }


    public function test_data_insertion_succeeds_with_valid_token(): void
    {
        $project = Models\Project::factory()->create();
        $token = $project->createToken('test_token', ['upload']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->postJson('/api/data', ['id' => 5, 'user' => 'john']);

        $response->assertCreated();
        $this->assertDatabaseHas('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_insertion_can_be_json(): void
    {
        $project = Models\Project::factory()->create();
        $token = $project->createToken('test_token', ['upload']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->postJson('/api/data', ['id' => 5, 'user' => 'john']);

        $response->assertCreated();
        $this->assertDatabaseHas('data', [
            'type' => 'json'
        ]);
    }


    public function test_data_insertion_can_be_csv(): void
    {
        $project = Models\Project::factory()->create();
        $token = $project->createToken('test_token', ['upload']);

        // Have to use raw `call` method since Laravel 11 (and consequently the withBody
        // method) isn't supported at OSU yet
        $response = $this->call(
            'POST',
            '/api/data',
            [],
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $token->plainTextToken,
                'CONTENT_TYPE' => 'text/csv'
            ],
            '5,john'
        );

        $response->assertCreated();
        $this->assertDatabaseCount('data', 1);
        $this->assertDatabaseHas('data', [
            'type' => 'csv',
            'data' => '5,john'
        ]);
    }


    public function test_data_insertion_can_be_undeclared(): void
    {
        $project = Models\Project::factory()->create();
        $token = $project->createToken('test_token', ['upload']);

        // Have to use raw `call` method since Laravel 11 (and consequently the withBody
        // method) isn't supported at OSU yet
        $response = $this->call(
            'POST',
            '/api/data',
            [],
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $token->plainTextToken],
            'some sort of data'
        );

        $response->assertCreated();
        $this->assertDatabaseHas('data', [
            'type' => 'unknown',
            'data' => 'some sort of data'
        ]);
    }


    public function test_data_update_succeeds_with_valid_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['upload']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->putJson('/api/data/' . $data->id, ['id' => 5, 'user' => 'john']);

        $response->assertOk();
        $this->assertDatabaseHas('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_deletion_succeeds_with_valid_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['upload']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->deleteJson('/api/data/' . $data->id);

        $response->assertOk();
        $this->assertDatabaseMissing('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
    }


    public function test_data_retrieval_fails_without_token(): void
    {
        $project = Models\Project::factory()->create();
        Models\Data::factory(20)->create(['project_id' => $project->id]);

        $response = $this
            ->getJson('/api/data');

        $response->assertUnauthorized();
        $response->assertContent('{"message":"Unauthenticated."}');
    }


    public function test_data_retrieval_fails_with_invalid_token(): void
    {
        $project = Models\Project::factory()->create();
        Models\Data::factory(20)->create(['project_id' => $project->id]);

        $response = $this
            ->withHeader('Authorization', 'Bearer s0meInv4l1dT0k3nW1th48Ch4r4ct3rs3456789012345678')
            ->getJson('/api/data');

        $response->assertUnauthorized();
        $response->assertContent('{"message":"Unauthenticated."}');
    }


    public function test_data_retrieval_fails_with_revoked_token(): void
    {
        $project = Models\Project::factory()->create();
        Models\Data::factory(20)->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['download']);
        $token->accessToken->delete();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->getJson('/api/data');

        $response->assertUnauthorized();
        $response->assertContent('{"message":"Unauthenticated."}');
    }


    public function test_data_retrieval_fails_with_upload_token(): void
    {
        $project = Models\Project::factory()->create();
        Models\Data::factory(20)->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['upload']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->getJson('/api/data');

        $response->assertForbidden();
    }


    public function test_data_insertion_fails_without_token(): void
    {
        $project = Models\Project::factory()->create();

        $response = $this
            ->postJson('/api/data', ['id' => 5, 'user' => 'john']);

        $response->assertUnauthorized();
        $this->assertDatabaseMissing('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_insertion_fails_with_invalid_token(): void
    {
        $project = Models\Project::factory()->create();
        $project->createToken('test_token', ['upload']);

        $response = $this
            ->withHeader('Authorization', 'Bearer s0meInv4l1dT0k3nW1th48Ch4r4ct3rs3456789012345678')
            ->postJson('/api/data', ['id' => 5, 'user' => 'john']);

        $response->assertUnauthorized();
        $this->assertDatabaseMissing('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_insertion_fails_with_revoked_token(): void
    {
        $project = Models\Project::factory()->create();
        $token = $project->createToken('test_token', ['upload']);
        $token->accessToken->delete();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->postJson('/api/data', ['id' => 5, 'user' => 'john']);

        $response->assertUnauthorized();
        $this->assertDatabaseMissing('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_insertion_fails_with_download_token(): void
    {
        $project = Models\Project::factory()->create();
        $token = $project->createToken('test_token', ['download']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->postJson('/api/data', ['id' => 5, 'user' => 'john']);

        $response->assertForbidden();
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

        $response->assertUnauthorized();
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
        $other_token = $other_project->createToken('test_token', ['upload']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $other_token->plainTextToken)
            ->putJson('/api/data/' . $data->id, ['id' => 5, 'user' => 'john']);

        $response->assertForbidden();
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
        $token = $project->createToken('test_token', ['upload']);
        $token->accessToken->delete();

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->putJson('/api/data/' . $data->id, ['id' => 5, 'user' => 'john']);

        $response->assertUnauthorized();
        $this->assertDatabaseHas('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
        $this->assertDatabaseMissing('data', [
            'data' => '{"id":5,"user":"john"}',
            'project_id' => $project->id
        ]);
    }


    public function test_data_update_fails_with_download_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['download']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
            ->putJson('/api/data/' . $data->id, ['id' => 5, 'user' => 'john']);

        $response->assertForbidden();
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

        $response->assertUnauthorized();
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
        $other_token = $other_project->createToken('test_token', ['upload']);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $other_token->plainTextToken)
            ->deleteJson('/api/data/' . $data->id);

        $response->assertForbidden();
        $this->assertDatabaseHas('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
    }


    public function test_data_deletion_fails_with_revoked_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['upload']);
        $token->accessToken->delete();

        $response = $this
        ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
        ->deleteJson('/api/data/' . $data->id);

        $response->assertUnauthorized();
        $this->assertDatabaseHas('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
    }


    public function test_data_deletion_fails_with_download_token(): void
    {
        $project = Models\Project::factory()->create();
        $data = Models\Data::factory()->create(['project_id' => $project->id]);
        $token = $project->createToken('test_token', ['download']);

        $response = $this
        ->withHeader('Authorization', 'Bearer ' . $token->plainTextToken)
        ->deleteJson('/api/data/' . $data->id);

        $response->assertForbidden();
        $this->assertDatabaseHas('data', [
            'data' => $data->data,
            'project_id' => $project->id
        ]);
    }
}
