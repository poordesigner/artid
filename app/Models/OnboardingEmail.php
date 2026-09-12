<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingEmail extends Model
{
    protected $fillable = [
        'artist_id',
        'step',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public static function alreadySent(int $artistId, string $step): bool
    {
        return static::where('artist_id', $artistId)
            ->where('step', $step)
            ->exists();
    }

    public static function markSent(int $artistId, string $step): static
    {
        return static::firstOrCreate(
            ['artist_id' => $artistId, 'step' => $step],
            ['sent_at' => now()]
        );
    }
}
