<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    protected $fillable = [
        'event_id',
        'event_type',
        'occurred_at',
        'processed_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
}