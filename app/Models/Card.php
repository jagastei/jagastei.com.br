<?php

declare(strict_types=1);

namespace App\Models;

use App\Helper;
use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Card extends Model
{
    use HasFactory;
    use HasSnowflakes;
    use SoftDeletes;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'account_id',
        'brand_id',
        'name',
        'limit',
        'digits',
        'credit',
        'virtual',
        'international',
    ];

    protected $casts = [
        'id' => Snowflake::class,
        'account_id' => Snowflake::class,
        'expiration_date' => 'date',
        'credit' => 'boolean',
        'virtual' => 'boolean',
        'international' => 'boolean',
    ];

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
