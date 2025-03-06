<?php

namespace App\Models;

use Akaunting\Money\Money;
use App\Helper;
use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory;
    use HasSnowflakes;
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
        'id' => Snowflake::class,
        // 'account_id' => Snowflake::class,
        'account_id' => 'string',
        'digital' => 'boolean',
        'credit' => 'boolean',
        'international' => 'boolean',
    ];

    protected $appends = [
        'formatted_limit',
    ];

    public function getFormattedLimitAttribute(): string
    {
        return Helper::formatMoney($this->limit);
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
