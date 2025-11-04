<?php

namespace App\Policies;

use App\Models\Data;
use App\Models\Project;

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
    public function delete(Project $project, Data $data): bool
    {
        return $data->project_id === $project->id;
    }
}
