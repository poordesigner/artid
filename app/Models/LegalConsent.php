<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalConsent extends Model
{
    protected $fillable = ['artist_id', 'type', 'version', 'granted', 'ip', 'user_agent'];

    protected function casts(): array
    {
        return ['granted' => 'boolean'];
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }
}
