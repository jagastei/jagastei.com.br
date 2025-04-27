<?php

namespace App\Models;

use App\Contracts\MustVerifyPhone;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

use function Illuminate\Events\queueable;

class User extends Authenticatable implements MustVerifyEmail, MustVerifyPhone
{
    use \App\Traits\MustVerifyPhone;
    use Billable;
    use HasFactory;
    use HasUuids;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'current_wallet_id',
        'language',
        'metadata',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::updated(queueable(function (User $customer) {
            if ($customer->hasStripeId()) {
                $customer->syncStripeCustomerDetails();
            }
        }));
    }

    public function getMetadata(?string $key = null): array|string|null
    {
        if ($key) {
            return $this->metadata[$key] ?? null;
        }

        return $this->metadata;
    }

    public function setMetadata(string $key, mixed $value): void
    {
        $this->metadata[$key] = $value;
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function currentWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'current_wallet_id');
    }

    public function routeNotificationForWhatsapp(): string
    {
        return $this->phone;
    }
}
