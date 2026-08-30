<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'support_ticket_id',
    'disk',
    'path',
    'original_name',
    'mime',
    'size',
])]
class SupportTicketAttachment extends Model
{
    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function humanSize(): string
    {
        $size = (int) $this->size;

        return $size >= 1048576
            ? round($size / 1048576, 1).' MB'
            : round($size / 1024, 1).' KB';
    }
}