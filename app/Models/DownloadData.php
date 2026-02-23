<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

class DownloadData extends Model
{
    use HasFactory;

    /**
     * The name of the table associated with this model (n.b. it doesn't end with "s")
     */
    protected $table = 'download_data';

    /**
     * Attributes that can be mass-filled using `DownloadData::create()`
     */
    protected $fillable = [
        'data'
    ];


    /**
     * Gets the project the data was created for
     */
    public function project(): Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
