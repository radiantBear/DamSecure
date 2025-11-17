<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        $permissions = $project->user_permissions()->where('user_id', $user->id)->get();

        return $permissions->isNotEmpty();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        $permissions = $project->user_permissions()->where('user_id', $user->id)->first();

        return $permissions && (
            $permissions->role === 'owner' || $permissions->role === 'contributor'
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        $permissions = $project->user_permissions()->where('user_id', $user->id);

        return $permissions && $permissions->role === 'owner';
    }
}
