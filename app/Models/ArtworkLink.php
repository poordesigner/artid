<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'artwork_id',
    'url',
    'type',
    'sort_order',
])]
class ArtworkLink extends Model
{
    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class);
    }
}