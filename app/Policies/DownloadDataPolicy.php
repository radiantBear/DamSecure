<?php

namespace App\Policies;

use App\Models\DownloadData;
use App\Models\Project;
use App\Models\User;

class DownloadDataPolicy
{
    /**
     * Determine whether the API key can be used to view the data record
     */
    public function view(Project $project): bool
    {
        return $project->tokenCan('download');
    }

    /**
     * Determine whether the user can update the data record
     */
    public function update(User $user, DownloadData $data): bool
    {
        $permissions = $data->project->user_permissions()->where('user_id', $user->id)->first();

        return $permissions && (
            $permissions->role === 'owner' || $permissions->role === 'contributor'
        );
    }
}
