<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanPeriod extends Model
{
    protected $fillable = [
        'plan_id',
        'number',
        'period',
        'price',
    ];

    protected $casts = [
        'number' => 'integer',
        'price' => 'decimal:2',
    ];

    const PERIODS = [
        'monthly' => 'Mensual',
        'quarterly' => 'Trimestral',
        'semiannual' => 'Semestral',
        'annual' => 'Anual',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function getPeriodLabelAttribute(): string
    {
        return self::PERIODS[$this->period] ?? $this->period;
    }
}
