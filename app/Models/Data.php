<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class Data extends Model
{
    use HasFactory;

    /**
     * The name of the table associated with this model (n.b. it doesn't end with "s")
     */
    protected $table = 'data';

    /**
     * Attributes that can be mass-filled using `Data::create()`
     */
    protected $fillable = [
        'data',
        'type'
    ];
    

    /**
     * Gets the project the data was uploaded for
     * 
     * @note Can create n + 1 issue if accessed in a loop and not eager-loaded (via 
     * `chaperone` method); see Eloquent Relationship "One to Many" docs
     */
    public function project(): Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
