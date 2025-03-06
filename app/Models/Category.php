<?php

namespace App\Models;

use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use HasSnowflakes;
    use SoftDeletes;

    protected $fillable = [
        'wallet_id',
        'name',
        'color',
        'icon',
        'emoji',
        'type',
    ];

    protected $casts = [
        'id' => Snowflake::class,
        'transactions_sum_value' => 'float',
    ];

    public function scopeOfWallet(Builder $query, Wallet $wallet): Builder
    {
        return $query->where('wallet_id', $wallet->id);
    }

    public function scopeIn(Builder $query): Builder
    {
        return $query->where('type', 'IN');
    }

    public function scopeOut(Builder $query): Builder
    {
        return $query->where('type', 'OUT');
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
