<?php

declare(strict_types=1);

namespace App\Models;

use App\Helper;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Budget extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'wallet_id',
        'name',
        'total',
        'current',
    ];

    protected $casts = [
        // 'id' => Snowflake::class,
        'wallet_id' => Snowflake::class,
    ];

    protected $appends = [
        'formatted_total',
        'formatted_current',
        'formatted_diff',
        'percentage',
    ];

    public function getFormattedTotalAttribute(): string
    {
        return Helper::formatMoney($this->total);
    }

    public function getFormattedCurrentAttribute(): string
    {
        return Helper::formatMoney($this->current);
    }

    public function getFormattedDiffAttribute(): string
    {
        return Helper::formatMoney($this->total - $this->current);
    }

    public function getPercentageAttribute(): float
    {
        return ($this->current / $this->total) * 100;
    }

    public function scopeOfWallet(Builder $query, Wallet $wallet): Builder
    {
        return $query->where('wallet_id', $wallet->id);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
