<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ownership extends Model
{
    protected $fillable = [
        'type',
        'owner_name',
        'owner_email',
        'transferred_at',
        'secret_key_hash',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'owner_name' => 'encrypted',
            'owner_email' => 'encrypted',
            'transferred_at' => 'date',
        ];
    }

    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class);
    }
}
