<?php

namespace Tests\Feature;

use App\Models;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;


    public function test_new_user_sees_no_projects_on_projects_page(): void
    {
        $user = Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/projects');

        $response->assertOk();
        $response->assertSee('No projects yet.');
        $response->assertSee('New project');
        $response->assertViewHas('projects', fn ($p) => $p->isEmpty());
    }


    public function test_user_sees_their_projects_on_projects_page(): void
    {
        $user = Models\User::factory()->create();
        $projects = Models\Project::factory(2)->create();
        foreach ($projects as $p) {
            Models\ProjectUser::factory()->create([
                'project_id' => $p->id,
                'user_id' => $user->id
            ]);
        }

        $response = $this->actingAs($user)->get('/projects');

        $response->assertOk();
        $response->assertViewHas('projects', fn ($p) => (
            $p->contains($projects[0])
            && $p->contains($projects[1])
        ));
    }


    public function test_user_does_not_see_other_projects_on_projects_page(): void
    {
        $user = Models\User::factory()->create();
        $user_projects = Models\Project::factory(2)->create();
        foreach ($user_projects as $p) {
            Models\ProjectUser::factory()->create([
                'project_id' => $p->id,
                'user_id' => $user->id
            ]);
        }

        $other_user = Models\User::factory()->create();
        $other_user_project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $other_user_project->id,
            'user_id' => $other_user->id
        ]);

        $response = $this->actingAs($user)->get('/projects');

        $response->assertOk();
        $response->assertViewHas('projects', fn ($p) => $p->doesntContain($other_user_project));
    }


    public function test_unauthenticated_user_cannot_see_projects_page(): void
    {
        $response = $this->get('/projects');
        $response->assertRedirect('/login');
    }


    public function test_user_can_create_projects(): void
    {
        $user = Models\User::factory()->create();

        $response = $this->actingAs($user)->post('/projects', [
            'name' => 'test_project'
        ]);

        $response->assertRedirectContains('/projects/');
        $this->assertDatabaseHas('projects', [
            'name' => 'test_project'
        ]);
        $this->assertDatabaseCount('project_users', 1);
        $this->assertDatabaseHas('project_users', [
            'project_id' => Models\Project::where('name', 'test_project')->first()->id,
            'user_id' => $user->id,
            'role' => 'owner'
        ]);
    }


    public function test_unauthenticated_user_cannot_create_projects(): void
    {
        $response = $this->post('/projects');
        $response->assertRedirect('/login');
    }


    public function test_user_can_see_project_details(): void
    {
        $user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);
        $data = Models\Data::factory(4)->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->get("/projects/{$project->uuid}");

        $response->assertOk();
    }


    public function test_user_cannot_see_other_project_details(): void
    {
        $user = Models\User::factory()->create();

        $other_user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $other_user->id
        ]);
        Models\Data::factory(4)->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->get("/projects/{$project->uuid}");

        $response->assertForbidden();
    }


    public function test_unauthenticated_user_cannot_see_project_details(): void
    {
        $user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);
        Models\Data::factory(4)->create(['project_id' => $project->id]);

        $response = $this->get("/projects/{$project->uuid}");

        $response->assertRedirect('/login');
    }


    public function test_user_can_rotate_project_token(): void
    {
        $user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'owner'
        ]);
        $token = $project->createToken('upload_token');

        $response = $this->actingAs($user)
            ->get("/projects/{$project->uuid}/token?expiration=year");

        $response->assertRedirect("/projects/{$project->uuid}");
        $this->assertDatabaseMissing('personal_access_tokens', [
            'token' => $token->accessToken->token
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 1);
        // NOTE: cannot directly assert that the token value displayed to the user is
        // correct since the plaintext isn't stored in the database
    }


    public function test_user_cannot_rotate_other_project_token(): void
    {
        $user = Models\User::factory()->create();

        $other_user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $other_user->id
        ]);
        $token = $project->createToken('upload_token');

        $response = $this->actingAs($user)->get("/projects/{$project->uuid}/token");

        $response->assertForbidden();
        $this->assertDatabaseHas('personal_access_tokens', [
            'token' => $token->accessToken->token
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }


    public function test_unauthenticated_user_cannot_rotate_project_token(): void
    {
        $user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);
        $token = $project->createToken('upload_token');

        $response = $this->get("/projects/{$project->uuid}/token");

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('personal_access_tokens', [
            'token' => $token->accessToken->token
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }
}
