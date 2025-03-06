<?php

namespace App\Models;

use App\Helper;
use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use HasSnowflakes;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'value',
        'category_id',
        'account_id',
        'method',
        'card_id',
    ];

    protected $casts = [
        'id' => Snowflake::class,
    ];

    protected $appends = [
        'formatted_value',
    ];

    public function getFormattedValueAttribute(): string
    {
        return Helper::formatMoney($this->value);
    }

    public function scopeOfWallet(Builder $query, Wallet $wallet): Builder
    {
        return $query->whereHas('account', function ($query) use ($wallet) {
            $query->ofWallet($wallet);
        });
    }

    public function scopeIn(Builder $query): Builder
    {
        return $query->where('type', 'IN');
    }

    public function scopeOut(Builder $query): Builder
    {
        return $query->where('type', 'OUT');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
