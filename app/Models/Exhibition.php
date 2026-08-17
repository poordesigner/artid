<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exhibition extends Model
{
    protected $fillable = ['name', 'date', 'description', 'links'];

    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class);
    }
}
