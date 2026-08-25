<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'artist_id',
        'plan_id',
        'plan_period_id',
        'paddle_customer_id',
        'paddle_subscription_id',
        'status',
        'next_billed_at',
        'current_period_start',
        'current_period_end',
        'canceled_at',
    ];

    protected $casts = [
        'next_billed_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public const STATUSES = [
        'trialing',
        'active',
        'past_due',
        'paused',
        'canceled',
        'expired',
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PlanPeriod::class, 'plan_period_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['trialing', 'active', 'past_due']);
    }

    public function hasScheduledCancellation(): bool
    {
        return $this->isActive() && $this->canceled_at !== null;
    }

    public function startedAt(): ?\Illuminate\Support\Carbon
    {
        return $this->current_period_start ?? $this->created_at;
    }

    public function endsAt(): ?\Illuminate\Support\Carbon
    {
        return $this->canceled_at ?? $this->current_period_end ?? $this->next_billed_at;
    }
}