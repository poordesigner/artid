<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'support_ticket_id',
    'status',
    'summary',
    'priority',
    'draft_reply',
    'suggested_actions',
    'analysis',
    'error',
    'model',
    'analyzed_at',
])]
class TicketAnalysis extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public const PRIORITY_NORMAL = 'normal';

    public const PRIORITY_ALTA = 'alta';

    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING], true);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function priorityLabel(): string
    {
        return match ($this->priority) {
            self::PRIORITY_ALTA => __('Alta'),
            default => __('Normal'),
        };
    }
}