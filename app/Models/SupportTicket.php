<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'number',
    'artist_id',
    'topic',
    'subject',
    'message',
    'status',
])]
class SupportTicket extends Model
{
    use HasFactory;

    public const TOPICS = ['cuenta', 'obras', 'facturacion', 'tecnico', 'otro'];

    public const TOPICS_LABELS = [
        'cuenta' => 'Cuenta',
        'obras' => 'Obras',
        'facturacion' => 'Facturación',
        'tecnico' => 'Técnico',
        'otro' => 'Otro',
    ];

    public const STATUS_OPEN = 'open';

    public const STATUS_CLOSED = 'closed';

    public function artist(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function analysis(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TicketAnalysis::class, 'support_ticket_id')->latestOfMany();
    }

    public function attachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupportTicketAttachment::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function topicLabel(): string
    {
        return match ($this->topic) {
            'cuenta' => __('Cuenta'),
            'obras' => __('Obras'),
            'facturacion' => __('Facturación'),
            'tecnico' => __('Técnico'),
            default => __('Otro'),
        };
    }
}