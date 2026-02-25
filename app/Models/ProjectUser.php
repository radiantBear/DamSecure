<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class ProjectUser extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass-filled using `ProjectUser::create()`
     *
     * Intentionally empty since this handles sensitive permissions that shouldn't
     * be exposed to user input
     */
    protected $fillable = [];


    /**
     * Gets the project the user access record is for
     *
     * @note Can create n + 1 issue if accessed in a loop and not eager-loaded (via
     * `chaperone` method); see Eloquent Relationship "One to Many" docs
     */
    public function project(): Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }


    /**
     * Gets the user the project access record is for
     *
     * @note Can create n + 1 issue if accessed in a loop and not eager-loaded (via
     * `chaperone` method); see Eloquent Relationship "One to Many" docs
     */
    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
