<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'artist_id',
    'title',
    'slug',
    'artwork_id',
    'public_id',
    'year',
    'edition',
    'edition_number',
    'creation_location',
    'status',
    'series',
    'series_id',
    'technique',
    'dimensions',
    'description',
    'location',
    'owner',
    'image',
    'short_url',
    'qr_code',
    'qr_validated_at',
    'qr_hash',
    'qr_validated_ip',
])]
class Artwork extends Model
{
    public const STATUSES = [
        'created',
        'exhibited',
        'sold',
        'transferred',
        'archived',
    ];

    protected static function booted(): void
    {
        static::creating(function (Artwork $artwork) {
            if (! $artwork->public_id) {
                $artwork->public_id = (string) Str::uuid();
            }
        });
    }

    public function isQrValidated(): bool
    {
        return $this->qr_validated_at !== null;
    }

    public function qrFieldsHash(): string
    {
        // 6 campos obligatorios: título, imagen, año, edición, nº copias, dimensiones
        // Si no es tiraje, nº copias = 1
        $imageHash = null;
        if ($this->image) {
            try {
                $path = "artworks/{$this->artwork_id}/{$this->image}";
                if (\Illuminate\Support\Facades\Storage::disk('r2')->exists($path)) {
                    $imageHash = hash('sha256', \Illuminate\Support\Facades\Storage::disk('r2')->get($path));
                }
            } catch (\Throwable $e) {}
        }
        $isTiraje = $this->edition && str_contains($this->edition, '/');
        $totalCopies = 1;
        if ($isTiraje) {
            $totalCopies = (int) trim(explode('/', $this->edition)[1] ?? 1);
        }
        $nCopias = $isTiraje ? ($this->edition_number ?? $totalCopies) : 1;
        $data = json_encode([
            'title' => $this->title,
            'image_hash' => $imageHash,
            'year' => $this->year,
            'edition' => $this->edition,
            'total_copies' => $totalCopies,
            'edition_number' => $nCopias,
            'dimensions' => $this->dimensions,
        ], JSON_UNESCAPED_UNICODE);
        return hash('sha256', $data);
    }

    protected function casts(): array
    {
        return [
            'qr_validated_at' => 'datetime',
        ];
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function exhibitions(): HasMany
    {
        return $this->hasMany(Exhibition::class);
    }

    public function ownerships(): HasMany
    {
        return $this->hasMany(Ownership::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(ArtworkLink::class)->orderBy('sort_order');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class)->orderByDesc('id');
    }

    /**
     * Firma HMAC versionada que vincula el QR con la ficha.
     */
    public function signature(): string
    {
        $version = (string) config('qrte.active_signing_version');

        return $version.'.'.hash_hmac('sha256', $this->public_id, (string) config('qrte.signing_keys.'.$version));
    }

    /**
     * Verifica una firma HMAC (soporta múltiples versiones de clave).
     */
    public function verifySignature(?string $signature): bool
    {
        if (! $signature) {
            return false;
        }

        $parts = explode('.', $signature, 2);

        if (count($parts) !== 2) {
            return false;
        }

        $key = config('qrte.signing_keys.'.$parts[0]);

        if (! $key) {
            return false;
        }

        return hash_equals(
            hash_hmac('sha256', $this->public_id, (string) $key),
            $parts[1]
        );
    }

    /**
     * URL pública firmada que codifica el QR.
     */
    public function signedUrl(): string
    {
        $base = rtrim((string) config('qrte.public_url'), '/');

        if (! preg_match('~^https?://~', $base)) {
            $base = 'https://'.$base;
        }

        return $base.'/o/'.$this->public_id.'?s='.$this->signature();
    }

    /**
     * URL pública de la imagen en R2.
     */
    public function imageUrl(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return rtrim((string) config('filesystems.disks.r2.url'), '/').'/artworks/'.$this->artwork_id.'/'.$this->image;
    }
}

