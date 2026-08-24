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

    /**
     * Texto de recurrencia gramaticalmente correcto.
     * Ej: 1 mensual -> "Cada mes", 2 anual -> "Cada 2 años"
     */
    public function recurrenceLabel(): string
    {
        return match ($this->period) {
            'monthly' => $this->number <= 1
                ? __('Cada mes')
                : __('Cada :count meses', ['count' => $this->number]),
            'quarterly' => $this->number <= 1
                ? __('Cada trimestre')
                : __('Cada :count trimestres', ['count' => $this->number]),
            'semiannual' => $this->number <= 1
                ? __('Cada semestre')
                : __('Cada :count semestres', ['count' => $this->number]),
            'annual' => $this->number <= 1
                ? __('Cada año')
                : __('Cada :count años', ['count' => $this->number]),
            default => $this->period_label,
        };
    }

    /**
     * Cantidad de meses que representa el periodo.
     */
    public function months(): int
    {
        return match ($this->period) {
            'monthly' => $this->number,
            'quarterly' => $this->number * 3,
            'semiannual' => $this->number * 6,
            'annual' => $this->number * 12,
            default => $this->number,
        };
    }

    /**
     * Precio equivalente mensual (total del periodo / meses).
     */
    public function monthlyEquivalent(): float
    {
        $months = $this->months();

        return $months > 0 ? round((float) $this->price / $months, 2) : 0.0;
    }
}
