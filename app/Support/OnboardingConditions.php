<?php

namespace App\Support;

use App\Models\Artist;

class OnboardingConditions
{
    public static function evaluate(string $condition, Artist $artist): bool
    {
        return match ($condition) {
            'always' => true,
            'has_tokens_and_no_artworks' => $artist->tokens_balance > 0 && ! $artist->artworks()->exists(),
            'has_no_artworks' => ! $artist->artworks()->exists(),
            'has_artworks' => $artist->artworks()->exists(),
            'low_tokens' => $artist->tokens_balance <= 2,
            default => false,
        };
    }
}
