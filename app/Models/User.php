<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;

use function Illuminate\Events\queueable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable;
    use HasFactory;
    use HasUuids;
    use Notifiable;

    protected $fillable = [
        'identifier',
        'name',
        'email',
        'password',
        'current_wallet_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (User $user) {
            $user->identifier = Str::random(7);

            $user->locale = 'pt-BR';
            $user->currency = 'BRL';
        });
    }

    protected static function booted(): void
    {
        static::updated(queueable(function (User $customer) {
            if ($customer->hasStripeId()) {
                $customer->syncStripeCustomerDetails();
            }
        }));
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function currentWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'current_wallet_id');
    }
}
