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

final class Account extends Model
{
    use HasFactory;
    use HasSnowflakes;
    use SoftDeletes;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'wallet_id',
        'bank_id',
        'name',
        'balance',
    ];

    protected $casts = [
        'id' => Snowflake::class,
        'wallet_id' => Snowflake::class,
    ];

    public function scopeOfWallet(Builder $query, Wallet $wallet): Builder
    {
        return $query->where('wallet_id', $wallet->id);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
