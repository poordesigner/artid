<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\ArtistFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'google_id', 'github_id', 'github_token', 'github_nickname', 'github_repo', 'short_domain', 'avatar', 'statement', 'cv_pdf', 'website_url', 'instagram', 'behance', 'artstation', 'youtube', 'tiktok', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class Artist extends Authenticatable
{
    /** @use HasFactory<ArtistFactory> */
    use HasFactory, Notifiable;

    public function artworks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Artwork::class);
    }

    public function series(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Series::class);
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Suscripción paga activa actual (o null si está en plan Free).
     */
    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->whereIn('status', ['trialing', 'active', 'past_due'])
            ->latest('id')
            ->first();
    }

    public function isOnFreePlan(): bool
    {
        return $this->activeSubscription() === null;
    }

    public function avatarUrl(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        return rtrim((string) config('filesystems.disks.r2.url'), '/').'/artists/'.$this->id.'/'.$this->avatar;
    }

    public function cvUrl(): ?string
    {
        if (! $this->cv_pdf) {
            return null;
        }

        return rtrim((string) config('filesystems.disks.r2.url'), '/').'/artists/'.$this->id.'/'.$this->cv_pdf;
    }

    public function socialUrl(string $network): ?string
    {
        $username = $this->{$network} ?? null;

        if (! $username) {
            return null;
        }

        $username = ltrim($username, '@');

        return match ($network) {
            'instagram' => 'https://instagram.com/'.$username,
            'behance' => 'https://behance.net/'.$username,
            'artstation' => 'https://www.artstation.com/'.$username,
            'youtube' => 'https://youtube.com/@'.$username,
            'tiktok' => 'https://tiktok.com/@'.$username,
            default => null,
        };
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'github_token' => 'encrypted',
            'is_admin' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }
}
