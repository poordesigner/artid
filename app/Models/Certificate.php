<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'artwork_id', 'artist_id', 'certificate_number', 'issued_at', 'issued_location',
        'holder_name', 'holder_type', 'custom_text', 'include_signature',
        'hash_image', 'hash_metadata', 'qr_payload', 'pdf_path', 'status', 'revoked_at',
        'terms_version', 'ip', 'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'revoked_at' => 'datetime',
            'include_signature' => 'boolean',
        ];
    }

    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class);
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function isValid(): bool
    {
        return $this->status === 'valid';
    }

    public function pdfUrl(): ?string
    {
        if (! $this->pdf_path) return null;
        return rtrim((string) config('filesystems.disks.r2.url'), '/').'/'.$this->pdf_path;
    }
}
