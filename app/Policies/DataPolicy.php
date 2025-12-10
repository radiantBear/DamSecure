<?php

namespace App\Policies;

use App\Models\Data;
use App\Models\Project;
use App\Models\User;

class DataPolicy
{
    /**
     * Determine whether the API key can be used to upload a data record
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the API key can be used to update the data record
     */
    public function update(Project $project, Data $data): bool
    {
        return $data->project_id === $project->id;
    }

    /**
     * Determine whether the API key can be used to delete the data record
     */
    public function delete($actor, Data $data): bool
    {
        if ($actor instanceof Project) {
            return $data->project_id === $actor->id;
        }

        if ($actor instanceof User) {
            $permissions = $data->project->user_permissions()->where('user_id', $actor->id)->first();

            return $permissions && ($permissions->role === 'owner' || $permissions->role === 'contributor');
        }

        return false;
    }
}
