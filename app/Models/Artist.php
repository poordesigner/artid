<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\ArtistFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'google_id', 'github_id', 'github_token', 'github_nickname', 'github_repo', 'short_domain', 'avatar', 'statement', 'cv_pdf', 'website_url', 'instagram', 'behance', 'artstation', 'youtube', 'tiktok', 'is_admin', 'granted_plan_id', 'granted_expires_at'])]
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
        return $this->activeSubscription() === null && $this->activeGrantedPlan() === null;
    }

    public function grantedPlan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Plan::class, 'granted_plan_id');
    }

    /**
     * Plan otorgado vigente (si no expiró), o null.
     */
    public function activeGrantedPlan(): ?Plan
    {
        if (! $this->granted_plan_id) {
            return null;
        }

        if ($this->granted_expires_at && $this->granted_expires_at->isPast()) {
            return null;
        }

        return $this->grantedPlan;
    }

    /**
     * Plan efectivo que determina los límites y features.
     * Prioridad: grant otorgado > suscripción paga > plan Free.
     */
    public function effectivePlan(): ?Plan
    {
        $granted = $this->activeGrantedPlan();
        if ($granted) {
            return $granted;
        }

        $subscriptionPlan = $this->activeSubscription()?->plan;
        if ($subscriptionPlan) {
            return $subscriptionPlan;
        }

        return Plan::where('is_free', true)->first();
    }

    /**
     * Límite de obras del plan efectivo del artista.
     */
    public function currentMaxArtworks(): ?int
    {
        return $this->effectivePlan()?->max_artworks ?? null;
    }

    /**
     * Cantidad de obras activas (no archivadas).
     */
    public function activeArtworksCount(): int
    {
        return $this->artworks()->where('status', '!=', 'archived')->count();
    }

    /**
     * Aplica los límites del plan: si hay más obras activas que el máximo,
     * archiva las más antiguas (conservando las últimas registradas).
     */
    public function enforcePlanLimits(): void
    {
        $max = $this->currentMaxArtworks();

        if ($max === null) {
            return;
        }

        $excess = $this->artworks()
            ->where('status', '!=', 'archived')
            ->latest('id')
            ->skip($max)
            ->pluck('id');

        if ($excess->isNotEmpty()) {
            Artwork::whereIn('id', $excess)->update(['status' => 'archived']);
        }
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
            'granted_expires_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }
}
