<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'artist_id',
        'subscription_id',
        'paddle_transaction_id',
        'status',
        'currency_code',
        'amount',
        'billed_at',
    ];

    protected $casts = [
        'billed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}