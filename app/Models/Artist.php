<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\ArtistFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'google_id', 'github_id', 'github_token', 'github_nickname', 'github_repo', 'short_domain', 'avatar', 'statement', 'cv_pdf', 'website_url', 'instagram', 'behance', 'artstation', 'youtube', 'tiktok', 'is_admin', 'granted_plan_id', 'granted_expires_at', 'tokens_balance', 'welcome_tokens_claimed'])]
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

    public function tokenTransactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TokenTransaction::class);
    }

    public function links(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ArtistLink::class)->orderBy('sort_order');
    }

    public function supportTickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ArtistNotification::class)->orderByDesc('id');
    }

    /**
     * Saldo actual de tokens disponible.
     */
    public function tokenBalance(): int
    {
        return (int) $this->tokens_balance;
    }

    /**
     * El artista puede crear una obra si le quedan tokens.
     */
    public function canCreateArtwork(): bool
    {
        return $this->tokenBalance() > 0;
    }

    /**
     * Acredita tokens (compra o grant). Actualiza el saldo atómicamente
     * y registra la transacción con el balance resultante.
     */
    public function addTokens(int $amount, string $type, ?string $ref = null, ?string $note = null): TokenTransaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('El monto debe ser mayor a cero.');
        }

        $this->increment('tokens_balance', $amount);
        $this->refresh();

        return $this->tokenTransactions()->create([
            'type' => $type,
            'amount' => $amount,
            'balance_after' => $this->tokens_balance,
            'ref' => $ref,
            'note' => $note,
        ]);
    }

    /**
     * Consume 1 token de forma atómica y segura.
     *
     * @return bool true si se pudo debitar (había saldo), false si no.
     */
    public function consumeToken(?string $note = null): bool
    {
        $updated = self::where('id', $this->id)
            ->where('tokens_balance', '>', 0)
            ->decrement('tokens_balance', 1);

        if (! $updated) {
            return false;
        }

        $this->refresh();

        $this->tokenTransactions()->create([
            'type' => 'consume',
            'amount' => -1,
            'balance_after' => $this->tokens_balance,
            'note' => $note,
        ]);

        return true;
    }

    /**
     * Otorga los tokens de bienvenida al primer registro.
     * Idempotente: solo se aplica una vez por artista.
     *
     * @return bool true si se otorgaron, false si ya estaban otorgados.
     */
    public function grantWelcomeTokens(): bool
    {
        if ($this->welcome_tokens_claimed) {
            return false;
        }

        $amount = (int) config('artid.welcome_tokens', 0);

        if ($amount <= 0) {
            $this->update(['welcome_tokens_claimed' => true]);

            return false;
        }

        $updated = \Illuminate\Support\Facades\DB::transaction(function () use ($amount) {
            $claimed = self::where('id', $this->id)
                ->where('welcome_tokens_claimed', false)
                ->update(['welcome_tokens_claimed' => true]);

            if (! $claimed) {
                return false;
            }

            $this->refresh();
            $this->increment('tokens_balance', $amount);
            $this->refresh();

            $this->tokenTransactions()->create([
                'type' => 'grant',
                'amount' => $amount,
                'balance_after' => $this->tokens_balance,
                'note' => __('Tokens de bienvenida (primer registro)'),
            ]);

            return true;
        });

        return (bool) $updated;
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
     * Aplica los límites del plan:
     * - Si hay más obras activas que el máximo, archiva las más antiguas.
     * - Si hay cupo libre, reactiva obras archivadas (las más recientes primero).
     */
    public function enforcePlanLimits(): void
    {
        $max = $this->currentMaxArtworks();

        if ($max === null) {
            return;
        }

        $activeIds = $this->artworks()
            ->where('status', '!=', 'archived')
            ->latest('id')
            ->pluck('id');

        // Archivar exceso (obras más antiguas).
        $excess = $activeIds->slice($max);
        if ($excess->isNotEmpty()) {
            Artwork::whereIn('id', $excess->toArray())->update(['status' => 'archived']);
        }

        // Reactivar obras archivadas si hay cupo (las más recientes primero).
        if ($activeIds->count() < $max) {
            $toRestore = $this->artworks()
                ->where('status', 'archived')
                ->latest('id')
                ->limit($max - $activeIds->count())
                ->pluck('id');

            if ($toRestore->isNotEmpty()) {
                Artwork::whereIn('id', $toRestore->toArray())->update(['status' => 'created']);
            }
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
            'tokens_balance' => 'integer',
            'welcome_tokens_claimed' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }
}
