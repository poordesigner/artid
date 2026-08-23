<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_value',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'base_value' => 'decimal:2',
        'is_active' => 'boolean',
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

    public function priceForPeriod(PlanPeriod $period): float
    {
        $months = match ($period->period) {
            'monthly' => $period->number,
            'quarterly' => $period->number * 3,
            'semiannual' => $period->number * 6,
            'annual' => $period->number * 12,
        };

        $baseTotal = (float) $this->base_value * $months;
        $discount = (float) $period->discount;

        return $baseTotal * (1 - $discount / 100);
    }
}
