<?php

namespace App\Models;

use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'account_id',
        'brand_id',
        'name',
        'limit',
        'digits',
        'digital',
        'credit',
        'international',
    ];

    protected $casts = [
        'digital' => 'boolean',
        'credit' => 'boolean',
        'international' => 'boolean',
    ];

    protected $appends = [
        'formatted_limit',
    ];

    public function getFormattedLimitAttribute(): string
    {
        return Money::BRL($this->limit ?? 0)->format();
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
}
