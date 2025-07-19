<?php

declare(strict_types=1);

namespace App\Models;

use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

final class Card extends Model
{
    use HasFactory;
    use HasSnowflakes;
    use Searchable;
    use SoftDeletes;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'account_id',
        'brand_id',
        'name',
        'limit',
        'digits',
        'expiration_date',
        'credit',
        'virtual',
        'international',
    ];

    protected $casts = [
        // 'id' => Snowflake::class,
        'id' => 'string',
        'account_id' => Snowflake::class,
        'expiration_date' => 'date',
        'credit' => 'boolean',
        'virtual' => 'boolean',
        'international' => 'boolean',
    ];

    protected $appends = [
        'expiration_month',
        'expiration_year',
        'last_digits',
        'formatted_expiration_date',
    ];

    public function toSearchableArray(): array
    {
        return [
            // 'id' => (string) $this->id->id(),
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->timestamp,
        ];
    }

    public function searchableAs(): string
    {
        return 'cards_index';
    }

    public function scopeOfWallet(Builder $query, Wallet $wallet): Builder
    {
        return $query->whereHas('account', function ($query) use ($wallet) {
            $query->ofWallet($wallet);
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function getExpirationMonthAttribute(): ?int
    {
        return $this->expiration_date?->month;
    }

    public function getExpirationYearAttribute(): ?int
    {
        return $this->expiration_date?->year;
    }

    public function getLastDigitsAttribute(): ?string
    {
        return $this->digits ? mb_substr($this->digits, -4) : null;
    }

    public function getFormattedExpirationDateAttribute(): ?string
    {
        return $this->expiration_date?->format('m/y');
    }
}
