<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class User extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass-filled using `User::create()`
     */
    protected $fillable = [
        'osu_uuid',
        'onid'
    ];

    /**
     * Attributes that should be hidden during serialization (e.g. for logs/responses)
     */
    protected $hidden = [
        'onid'
    ];
    
    /**
     * Gets the user's project permissions
     */
    public function project_permissions(): Relations\HasMany {
        // Automatically maps via project_user.user_id
        return $this->hasMany(ProjectUser::class);
    }
}
