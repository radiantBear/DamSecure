<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class Project extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass-filled using `Project::create()`
     */
    protected $fillable = [
        'uuid',
        'name',
        'api_key'
    ];

    
    /**
     * Gets the user permissions for the project
     */
    public function user_permissions(): Relations\HasMany {
        // Automatically maps via project_user.project_id
        return $this->hasMany(ProjectUser::class);
    }


    /**
     * Gets the data uploaded for the project
     */
    public function project_data(): Relations\HasMany {
        // Automatically maps via data.project_id
        return $this->hasMany(Data::class);
    }
}
