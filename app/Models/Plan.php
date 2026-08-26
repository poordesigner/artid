<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'sort_order',
        'paddle_product_id',
        'max_artworks',
        'is_free',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_free' => 'boolean',
        'max_artworks' => 'integer',
    ];

    public function periods(): HasMany
    {
        return $this->hasMany(PlanPeriod::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function legalTerms(): HasMany
    {
        return $this->hasMany(PlanLegalTerm::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
