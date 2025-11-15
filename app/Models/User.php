<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * Attributes that can be mass-filled using `User::create()`
     */
    protected $fillable = [
        'osuuid',
        'onid',
        'firstName',
        'lastName',
        'email'
    ];

    /**
     * Attributes that should be hidden during serialization (e.g. for logs/responses)
     */
    protected $hidden = [
        'onid',
        'firstName',
        'lastName',
        'email'
    ];
    
    /**
     * Gets the user's project permissions
     */
    public function project_permissions(): Relations\HasMany {
        // Automatically maps via project_user.user_id
        return $this->hasMany(ProjectUser::class);
    }
}
