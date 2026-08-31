<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'support_ticket_id',
    'sender',
    'body',
    'sent_at',
])]
class SupportTicketReply extends Model
{
    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }
}