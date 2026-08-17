<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'artist_id',
    'title',
    'slug',
    'artwork_id',
    'year',
    'edition',
    'status',
    'series',
    'technique',
    'dimensions',
    'description',
    'location',
    'owner',
    'image',
    'short_url',
    'qr_code',
])]
class Artwork extends Model
{
    public const STATUSES = [
        'created',
        'exhibited',
        'sold',
        'transferred',
        'archived',
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }
}
