<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class TestData extends Model
{
    use HasFactory;

    /**
     * The name of the table associated with this model (n.b. it doesn't end with "s")
     */
    protected $table = 'test_data';

    /**
     * Attributes that can be mass-filled using `TestData::create()`
     */
    protected $fillable = [
        'data',
        'latest_times_retrieved',
        'total_times_retrieved'
    ];


    /**
     * Gets the project the data was created for
     */
    public function project(): Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
