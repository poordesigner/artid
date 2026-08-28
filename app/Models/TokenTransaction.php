<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'artist_id',
    'type',
    'amount',
    'balance_after',
    'ref',
    'note',
])]
class TokenTransaction extends Model
{
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }
}