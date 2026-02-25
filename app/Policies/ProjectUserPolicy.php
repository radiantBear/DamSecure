<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectUserPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function viewAny(User $user, Project $project): bool
    {
        $permissions = $project->user_permissions()->where('user_id', $user->id)->get();

        return $permissions->isNotEmpty();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        $permissions = $project->user_permissions()->where('user_id', $user->id)->first();

        return $permissions?->role === 'owner';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectUser $project_user): bool
    {
        $permissions = $project_user->project->user_permissions()->where('user_id', $user->id)->first();

        return $permissions?->role === 'owner' && $user->id !== $project_user->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectUser $project_user): bool
    {
        $permissions = $project_user->project->user_permissions()->where('user_id', $user->id)->first();

        return $permissions?->role === 'owner' && $user->id !== $project_user->user_id;
    }
}
