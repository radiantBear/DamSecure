<?php

namespace Tests\Feature;

use App\Models;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectUserTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_sees_all_members_on_members_page(): void
    {
        $user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'viewer'
        ]);
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => Models\User::factory()->create(),
            'role' => 'owner'
        ]);

        $response = $this->actingAs($user)->get("/projects/{$project->uuid}/permissions");

        $response->assertOk();
        $response->assertViewHas('permissions', $project->user_permissions()->with('user')->get());
    }


    public function test_user_cannot_see_other_projects_members_page(): void
    {
        $user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => Models\User::factory()->create(),
            'role' => 'owner'
        ]);

        $response = $this->actingAs($user)->get("/projects/{$project->uuid}/permissions");

        $response->assertForbidden();
    }


    public function test_unauthenticated_user_cannot_see_members_page(): void
    {
        $project = Models\Project::factory()->create();

        $response = $this->get("/projects/{$project->uuid}/permissions");
        $response->assertRedirect('/login');
    }


    public function test_owner_can_add_member_by_onid(): void
    {
        $user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'owner'
        ]);

        $newUser = Models\User::factory()->create();

        $response = $this->actingAs($user)->post(
            "/projects/{$project->uuid}/permissions",
            ['onid' => $newUser->onid, 'role' => 'contributor']
        );

        $response->assertOk();
        $this->assertDatabaseCount('project_users', 2);
        $this->assertDatabaseHas('project_users', [
            'project_id' => $project->id,
            'user_id' => $newUser->id,
            'role' => 'contributor'
        ]);
    }


    public function test_contributor_cannot_add_member(): void
    {
        $user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'contributor'
        ]);

        $newUser = Models\User::factory()->create();

        $response = $this->actingAs($user)->post(
            "/projects/{$project->uuid}/permissions",
            ['onid' => $newUser->onid, 'role' => 'contributor']
        );

        $response->assertForbidden();
        $this->assertDatabaseCount('project_users', 1);
    }


    public function test_user_cannot_add_member_to_other_project(): void
    {
        $user = Models\User::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => Models\Project::factory()->create(),
            'user_id' => $user->id,
            'role' => 'owner'
        ]);

        $project = Models\Project::factory()->create();
        $newUser = Models\User::factory()->create();

        $response = $this->actingAs($user)->post(
            "/projects/{$project->uuid}/permissions",
            ['onid' => $newUser->onid, 'role' => 'contributor']
        );

        $response->assertForbidden();
        $this->assertDatabaseCount('project_users', 1);
    }


    public function test_unauthenticated_user_cannot_add_members(): void
    {
        $project = Models\Project::factory()->create();
        $newUser = Models\User::factory()->create();

        $response = $this->post(
            "/projects/{$project->uuid}/permissions",
            ['onid' => $newUser->onid, 'role' => 'contributor']
        );

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('project_users', 0);
    }


    public function test_cannot_add_member_with_nonexistent_onid(): void
    {
        $user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'owner'
        ]);

        $response = $this->actingAs($user)->post(
            "/projects/{$project->uuid}/permissions",
            ['onid' => 'fakeOnid', 'role' => 'contributor']
        );

        $response->assertRedirect();
        $this->assertDatabaseCount('project_users', 1);
    }


    public function test_cannot_add_member_with_invalid_permission(): void
    {
        $user = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'owner'
        ]);

        $newUser = Models\User::factory()->create();

        $response = $this->actingAs($user)->post(
            "/projects/{$project->uuid}/permissions",
            ['onid' => $newUser->onid, 'role' => 'somethingFake']
        );

        $response->assertRedirect();
        $this->assertDatabaseCount('project_users', 1);
    }


    public function test_cannot_add_member_already_added(): void
    {
        $user = Models\User::factory()->create();
        $newUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->createMany([
            [
                'project_id' => $project->id,
                'user_id' => $user->id,
                'role' => 'owner'
            ],
            [
                'project_id' => $project->id,
                'user_id' => $newUser->id,
                'role' => 'contributor'
            ]
        ]);

        $response = $this->actingAs($user)->post(
            "/projects/{$project->uuid}/permissions",
            ['onid' => $newUser->onid, 'role' => 'owner']
        );

        $response->assertRedirect();
        $this->assertDatabaseCount('project_users', 2);
        $this->assertDatabaseHas('project_users', [
            'project_id' => $project->id,
            'user_id' => $newUser->id,
            'role' => 'contributor'
        ]);
    }


    public function test_owner_can_update_member_permissions(): void
    {
        $user = Models\User::factory()->create();
        $updatedUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'owner'
        ]);
        $updatedMember = Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $updatedUser,
            'role' => 'viewer'
        ]);

        $response = $this->actingAs($user)->patch(
            "/permissions/{$updatedMember->id}",
            ['role' => 'contributor']
        );

        $response->assertRedirect("projects/{$project->uuid}/permissions");
        $this->assertDatabaseCount('project_users', 2);
        $this->assertDatabaseHas('project_users', [
            'project_id' => $project->id,
            'user_id' => $updatedUser->id,
            'role' => 'contributor'
        ]);
    }


    public function test_contributor_cannot_update_member_permissions(): void
    {
        $user = Models\User::factory()->create();
        $updatedUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'contributor'
        ]);
        $updatedMember = Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $updatedUser,
            'role' => 'viewer'
        ]);

        $response = $this->actingAs($user)->patch(
            "/permissions/{$updatedMember->id}",
            ['role' => 'contributor']
        );

        $response->assertForbidden();
        $this->assertDatabaseCount('project_users', 2);
        $this->assertDatabaseHas('project_users', [
            'project_id' => $project->id,
            'user_id' => $updatedUser->id,
            'role' => 'viewer'
        ]);
    }


    public function test_user_cannot_update_permissions_for_other_projects(): void
    {
        $user = Models\User::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => Models\Project::factory()->create(),
            'user_id' => $user->id,
            'role' => 'owner'
        ]);

        $updatedUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        $updatedMember = Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $updatedUser->id,
            'role' => 'viewer'
        ]);


        $response = $this->actingAs($user)->patch(
            "/permissions/{$updatedMember->id}",
            ['role' => 'contributor']
        );

        $response->assertForbidden();
        $this->assertDatabaseCount('project_users', 2);
        $this->assertDatabaseHas('project_users', [
            'project_id' => $project->id,
            'user_id' => $updatedUser->id,
            'role' => 'viewer'
        ]);
    }


    public function test_unauthenticated_user_cannot_update_permissions(): void
    {
        $updatedUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        $updatedMember = Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $updatedUser,
            'role' => 'viewer'
        ]);

        $response = $this->patch(
            "/permissions/{$updatedMember->id}",
            ['role' => 'contributor']
        );

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('project_users', 1);
        $this->assertDatabaseHas('project_users', [
            'project_id' => $project->id,
            'user_id' => $updatedUser->id,
            'role' => 'viewer'
        ]);
    }


    public function test_cannot_update_with_invalid_permissions(): void
    {
        $user = Models\User::factory()->create();
        $updatedUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'owner'
        ]);
        $updatedMember = Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $updatedUser,
            'role' => 'viewer'
        ]);

        $response = $this->actingAs($user)->patch(
            "/permissions/{$updatedMember->id}",
            ['role' => 'somethingFake']
        );

        $response->assertRedirect();
        $this->assertDatabaseCount('project_users', 2);
        $this->assertDatabaseHas('project_users', [
            'project_id' => $project->id,
            'user_id' => $updatedUser->id,
            'role' => 'viewer'
        ]);
    }


    public function test_owner_can_remove_members(): void
    {
        $user = Models\User::factory()->create();
        $removedUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'owner'
        ]);
        $removedMember = Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $removedUser,
            'role' => 'viewer'
        ]);

        $response = $this->actingAs($user)->delete("/permissions/{$removedMember->id}");

        $response->assertRedirect("projects/{$project->uuid}/permissions");
        $this->assertDatabaseCount('project_users', 1);
        $this->assertDatabaseMissing('project_users', [
            'project_id' => $project->id,
            'user_id' => $removedUser->id
        ]);
    }


    public function test_contributor_cannot_remove_members(): void
    {
        $user = Models\User::factory()->create();
        $removedUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'contributor'
        ]);
        $removedMember = Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $removedUser,
            'role' => 'viewer'
        ]);

        $response = $this->actingAs($user)->delete("/permissions/{$removedMember->id}");

        $response->assertForbidden();
        $this->assertDatabaseCount('project_users', 2);
        $this->assertDatabaseHas('project_users', [
            'project_id' => $project->id,
            'user_id' => $removedUser->id,
            'role' => 'viewer'
        ]);
    }


    public function test_user_cannot_remove_members_for_other_projects(): void
    {
        $user = Models\User::factory()->create();
        Models\ProjectUser::factory()->create([
            'project_id' => Models\Project::factory()->create(),
            'user_id' => $user->id,
            'role' => 'owner'
        ]);

        $removedUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        $removedMember = Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $removedUser->id,
            'role' => 'viewer'
        ]);


        $response = $this->actingAs($user)->delete("/permissions/{$removedMember->id}");

        $response->assertForbidden();
        $this->assertDatabaseCount('project_users', 2);
        $this->assertDatabaseHas('project_users', [
            'project_id' => $project->id,
            'user_id' => $removedUser->id,
            'role' => 'viewer'
        ]);
    }


    public function test_unauthenticated_user_cannot_remove_members(): void
    {
        $removedUser = Models\User::factory()->create();
        $project = Models\Project::factory()->create();
        $removedMember = Models\ProjectUser::factory()->create([
            'project_id' => $project->id,
            'user_id' => $removedUser,
            'role' => 'viewer'
        ]);

        $response = $this->patch(
            "/permissions/{$removedMember->id}",
            ['role' => 'contributor']
        );

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('project_users', 1);
        $this->assertDatabaseHas('project_users', [
            'project_id' => $project->id,
            'user_id' => $removedUser->id,
            'role' => 'viewer'
        ]);
    }
}
